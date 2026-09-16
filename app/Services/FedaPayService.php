<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Webhook;
use RuntimeException;

class FedaPayService
{
    public function isConfigured(): bool
    {
        return filled(config('foodlab.payments.fedapay.secret_key'));
    }

    public function environment(): string
    {
        $mode = strtolower((string) config('foodlab.payments.fedapay.mode', 'sandbox'));

        return in_array($mode, ['live', 'production'], true) ? 'live' : 'sandbox';
    }

    public function isSandbox(): bool
    {
        return $this->environment() === 'sandbox';
    }

    public function configureSdk(): void
    {
        $secret = config('foodlab.payments.fedapay.secret_key');
        if (! $secret) {
            throw new RuntimeException('FEDAPAY_SECRET_KEY manquante.');
        }

        FedaPay::setApiKey($secret);
        FedaPay::setEnvironment($this->environment());
    }

    /**
     * Create a FedaPay transaction and return checkout URL + transaction id.
     *
     * @return array{transaction_id: string|int, url: string, token: ?string}
     */
    public function createCheckout(Payment $payment, User $user): array
    {
        $this->configureSdk();

        [$firstname, $lastname] = $this->splitName($user->name);

        $transaction = Transaction::create([
            'description' => 'FoodLab Academy — plan '.ucfirst($payment->plan).' #'.$payment->id,
            'amount' => (int) $payment->amount,
            'currency' => ['iso' => strtoupper($payment->currency ?: 'XOF')],
            'callback_url' => route('payments.fedapay.callback', $payment),
            'custom_metadata' => [
                'payment_id' => (string) $payment->id,
                'plan' => $payment->plan,
                'user_id' => (string) $user->id,
            ],
            'customer' => array_filter([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $user->email,
            ]),
        ]);

        $token = $transaction->generateToken();
        $url = $token->url ?? null;

        if (! $url) {
            throw new RuntimeException('FedaPay n\'a pas renvoyé d\'URL de paiement.');
        }

        return [
            'transaction_id' => $transaction->id,
            'url' => $url,
            'token' => $token->token ?? null,
        ];
    }

    /**
     * Verify webhook signature when secret is configured; otherwise decode JSON.
     *
     * @return object Event-like object with name/entity fields
     */
    public function parseWebhook(string $payload, ?string $signatureHeader): object
    {
        $secret = config('foodlab.payments.fedapay.webhook_secret');

        if ($secret && $signatureHeader) {
            return Webhook::constructEvent($payload, $signatureHeader, $secret);
        }

        if ($secret && ! $signatureHeader) {
            throw new RuntimeException('Signature webhook FedaPay manquante.');
        }

        $event = json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        if (! is_object($event)) {
            throw new RuntimeException('Payload webhook FedaPay invalide.');
        }

        return $event;
    }

    /**
     * Extract payment id + external transaction id from a FedaPay event or callback payload.
     *
     * @return array{payment_id: ?string, external_id: ?string, status: ?string, approved: bool}
     */
    public function extractPaymentRefs(object|array $event): array
    {
        $data = is_array($event) ? $event : json_decode(json_encode($event), true);
        $name = strtolower((string) ($data['name'] ?? $data['type'] ?? ''));

        $entity = $data['entity'] ?? $data['data']['object'] ?? $data['object'] ?? $data;
        if (is_object($entity)) {
            $entity = json_decode(json_encode($entity), true);
        }
        if (! is_array($entity)) {
            $entity = [];
        }

        $metadata = $entity['custom_metadata'] ?? $entity['metadata'] ?? [];
        if (is_object($metadata)) {
            $metadata = (array) $metadata;
        }

        $externalId = isset($entity['id']) ? (string) $entity['id'] : ($data['id'] ?? null);
        $status = strtolower((string) ($entity['status'] ?? $data['status'] ?? ''));
        $paymentId = $metadata['payment_id'] ?? $data['payment_id'] ?? null;

        $approved = in_array($name, ['transaction.approved', 'transaction.transferred'], true)
            || in_array($status, ['approved', 'transferred', 'paid', 'complete', 'completed', 'success'], true);

        return [
            'payment_id' => $paymentId !== null ? (string) $paymentId : null,
            'external_id' => $externalId !== null ? (string) $externalId : null,
            'status' => $status ?: ($approved ? 'approved' : null),
            'approved' => $approved,
            'event_name' => $name ?: null,
        ];
    }

    protected function splitName(?string $fullName): array
    {
        $fullName = trim((string) $fullName);
        if ($fullName === '') {
            return ['Client', 'FoodLab'];
        }

        $parts = preg_split('/\s+/', $fullName, 2) ?: [$fullName];

        return [
            $parts[0] ?: 'Client',
            $parts[1] ?? 'FoodLab',
        ];
    }
}
