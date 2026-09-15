<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function plans(): View
    {
        return view('payments.plans', [
            'plans' => config('foodlab.plans'),
            'mmProvider' => config('foodlab.payments.default_mm_provider'),
        ]);
    }

    public function checkout(Request $request, PaymentActivationService $activation): View|RedirectResponse
    {
        $data = $request->validate([
            'plan' => ['required', 'in:starter,premium'],
            'provider' => ['required', 'in:stripe,kkiapay,fedapay,fake'],
        ]);

        $user = $request->user();
        if ($user->plan === 'premium' && $data['plan'] === 'starter') {
            return back()->with('error', 'Vous êtes déjà en Premium.');
        }

        $payment = $activation->createPending($user, $data['plan'], $data['provider']);

        if ($data['provider'] === 'fake' || app()->environment('local', 'testing')) {
            if ($data['provider'] === 'fake' || $request->boolean('simulate')) {
                return view('payments.checkout', [
                    'payment' => $payment,
                    'plans' => config('foodlab.plans'),
                    'simulate' => true,
                ]);
            }
        }

        if ($data['provider'] === 'stripe') {
            return $this->stripeCheckout($payment);
        }

        return view('payments.checkout', [
            'payment' => $payment,
            'plans' => config('foodlab.plans'),
            'simulate' => true,
            'mmProvider' => $data['provider'],
        ]);
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
        } catch (\Throwable $e) {
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

    public function mobileMoneyWebhook(Request $request, PaymentActivationService $activation)
    {
        $provider = config('foodlab.payments.default_mm_provider', 'kkiapay');
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
