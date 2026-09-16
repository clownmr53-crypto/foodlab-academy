<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\FedaPayService;
use App\Services\PaymentActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;
use Throwable;

class PaymentController extends Controller
{
    public function plans(): View
    {
        return view('payments.plans', [
            'plans' => config('foodlab.plans'),
            'mmProvider' => config('foodlab.payments.default_mm_provider'),
            'fedapayReady' => app(FedaPayService::class)->isConfigured(),
        ]);
    }

    public function checkout(Request $request, PaymentActivationService $activation, FedaPayService $fedaPay): View|RedirectResponse
    {
        $data = $request->validate([
            'plan' => ['required', 'in:starter,premium'],
            'provider' => ['required', 'in:stripe,kkiapay,fedapay,fake'],
        ]);

        $user = $request->user();
        if ($user->plan === 'premium' && $data['plan'] === 'starter') {
            return back()->with('error', 'Vous êtes déjà en Premium.');
        }

        $planConfig = config('foodlab.plans.'.$data['plan']);
        $payment = $activation->createPending($user, $data['plan'], $data['provider']);

        // Starter gratuit (0 FCFA) : activation immédiate sans passer par un PSP
        if ((int) ($planConfig['price'] ?? 0) <= 0) {
            $activation->activate($payment);

            return redirect()->route('payments.success', $payment)
                ->with('status', 'Plan Starter activé gratuitement.');
        }

        if ($data['provider'] === 'fake' || ($request->boolean('simulate') && app()->environment('local', 'testing'))) {
            return view('payments.checkout', [
                'payment' => $payment,
                'plans' => config('foodlab.plans'),
                'simulate' => true,
                'mmProvider' => $data['provider'],
            ]);
        }

        if ($data['provider'] === 'stripe') {
            return $this->stripeCheckout($payment);
        }

        if ($data['provider'] === 'fedapay') {
            return $this->fedaPayCheckout($payment, $user, $fedaPay);
        }

        // KKiaPay / autres MM : simulation tant que non câblé
        return view('payments.checkout', [
            'payment' => $payment,
            'plans' => config('foodlab.plans'),
            'simulate' => true,
            'mmProvider' => $data['provider'],
            'message' => 'Mobile Money ('.$data['provider'].') — mode simulation (clés / intégration manquantes).',
        ]);
    }

    protected function fedaPayCheckout(Payment $payment, $user, FedaPayService $fedaPay): View|RedirectResponse
    {
        if (! $fedaPay->isConfigured()) {
            return view('payments.checkout', [
                'payment' => $payment,
                'plans' => config('foodlab.plans'),
                'simulate' => true,
                'mmProvider' => 'fedapay',
                'message' => 'FedaPay non configuré (FEDAPAY_SECRET_KEY manquante) — mode simulation activé.',
            ]);
        }

        try {
            $checkout = $fedaPay->createCheckout($payment, $user);
        } catch (Throwable $e) {
            report($e);

            return view('payments.checkout', [
                'payment' => $payment,
                'plans' => config('foodlab.plans'),
                'simulate' => true,
                'mmProvider' => 'fedapay',
                'message' => 'Impossible de joindre FedaPay — mode simulation activé. ('.$e->getMessage().')',
            ]);
        }

        $payment->update([
            'external_id' => (string) $checkout['transaction_id'],
            'payload' => array_merge($payment->payload ?? [], [
                'checkout_url' => $checkout['url'],
                'fedapay_token' => $checkout['token'] ?? null,
            ]),
        ]);

        return redirect()->away($checkout['url']);
    }

    protected function stripeCheckout(Payment $payment): View|RedirectResponse
    {
        $secret = config('foodlab.payments.stripe.secret');
        if (! $secret) {
            return view('payments.checkout', [
                'payment' => $payment,
                'plans' => config('foodlab.plans'),
                'simulate' => true,
                'message' => 'Stripe non configuré — mode simulation activé.',
            ]);
        }

        Stripe::setApiKey($secret);
        $session = StripeSession::create([
            'mode' => 'payment',
            'success_url' => route('payments.success', ['payment' => $payment->id]).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payments.plans'),
            'client_reference_id' => (string) $payment->id,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($payment->currency) === 'xof' ? 'xof' : strtolower($payment->currency),
                    'unit_amount' => $payment->amount,
                    'product_data' => [
                        'name' => 'FoodLab '.$payment->plan,
                    ],
                ],
            ]],
            'metadata' => [
                'payment_id' => $payment->id,
                'plan' => $payment->plan,
            ],
        ]);

        $payment->update(['external_id' => $session->id, 'payload' => ['checkout_url' => $session->url]]);

        return redirect()->away($session->url);
    }

    public function simulateSuccess(Request $request, Payment $payment, PaymentActivationService $activation): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);
        abort_unless(in_array($payment->status, ['pending', 'failed'], true), 422);

        $activation->activate($payment);

        return redirect()->route('payments.success', $payment);
    }

    public function success(Request $request, Payment $payment): View
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        return view('payments.success', compact('payment'));
    }

    /**
     * Retour navigateur après paiement FedaPay (callback_url).
     */
    public function fedaPayCallback(Request $request, Payment $payment, PaymentActivationService $activation, FedaPayService $fedaPay): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        $status = strtolower((string) $request->query('status', ''));
        $txId = $request->query('id') ?? $request->query('transaction_id');

        if ($txId) {
            $payment->external_id = (string) $txId;
            $payment->provider = 'fedapay';
            $payment->payload = array_merge($payment->payload ?? [], [
                'callback' => $request->query(),
            ]);
            $payment->save();
        }

        $approved = in_array($status, ['approved', 'transferred', 'paid', 'complete', 'completed', 'success'], true);
        if ($approved && in_array($payment->status, ['pending', 'failed'], true)) {
            $activation->activate($payment);
        }

        return redirect()->route('payments.success', $payment)
            ->with('status', $approved
                ? 'Paiement FedaPay confirmé.'
                : 'Retour FedaPay reçu — statut : '.($status ?: 'en attente (webhook).'));
    }

    public function stripeWebhook(Request $request, PaymentActivationService $activation)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        $secret = config('foodlab.payments.stripe.webhook_secret');

        try {
            if ($secret && $sig) {
                $event = Webhook::constructEvent($payload, $sig, $secret);
            } else {
                $event = json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
            }
        } catch (Throwable $e) {
            return response('Invalid payload', 400);
        }

        $type = is_object($event) ? ($event->type ?? null) : null;
        if ($type === 'checkout.session.completed') {
            $session = $event->data->object;
            $paymentId = $session->metadata->payment_id ?? $session->client_reference_id ?? null;
            if ($paymentId) {
                $payment = Payment::find($paymentId);
                if ($payment) {
                    $payment->external_id = $session->id ?? $payment->external_id;
                    $payment->save();
                    $activation->activate($payment);
                }
            }
        }

        return response('ok');
    }

    public function mobileMoneyWebhook(Request $request, PaymentActivationService $activation, FedaPayService $fedaPay)
    {
        $provider = config('foodlab.payments.default_mm_provider', 'fedapay');
        $payload = $request->getContent() ?: json_encode($request->all());

        // FedaPay signed webhooks
        if ($provider === 'fedapay' || $request->header('X-FEDAPAY-SIGNATURE')) {
            try {
                $event = $fedaPay->parseWebhook(
                    is_string($payload) ? $payload : json_encode($payload),
                    $request->header('X-FEDAPAY-SIGNATURE')
                );
            } catch (Throwable $e) {
                return response()->json(['error' => 'Invalid FedaPay webhook'], 400);
            }

            $refs = $fedaPay->extractPaymentRefs($event);
            $payment = null;
            if ($refs['payment_id'] && is_numeric($refs['payment_id'])) {
                $payment = Payment::find($refs['payment_id']);
            }
            if (! $payment && $refs['external_id']) {
                $payment = Payment::query()->where('external_id', $refs['external_id'])->first();
            }

            if ($payment && $refs['approved']) {
                if ($refs['external_id']) {
                    $payment->external_id = $refs['external_id'];
                }
                $payment->provider = 'fedapay';
                $payment->payload = array_merge($payment->payload ?? [], [
                    'webhook' => json_decode(json_encode($event), true),
                ]);
                $payment->save();
                $activation->activate($payment);
            }

            return response()->json(['ok' => true]);
        }

        // Legacy / KKiaPay-style payload
        $data = $request->all();
        $externalId = $data['transactionId'] ?? $data['id'] ?? $data['transaction_id'] ?? null;
        $paymentId = $data['payment_id'] ?? $data['state'] ?? null;
        $status = strtolower((string) ($data['status'] ?? $data['state'] ?? 'success'));

        $payment = null;
        if ($paymentId && is_numeric($paymentId)) {
            $payment = Payment::find($paymentId);
        }
        if (! $payment && $externalId) {
            $payment = Payment::query()->where('external_id', $externalId)->first();
        }

        if ($payment && in_array($status, ['success', 'approved', 'complete', 'completed', 'paid'], true)) {
            if ($externalId) {
                $payment->external_id = $externalId;
            }
            $payment->provider = $provider;
            $payment->payload = array_merge($payment->payload ?? [], $data);
            $payment->save();
            $activation->activate($payment);
        }

        return response()->json(['ok' => true]);
    }
}
