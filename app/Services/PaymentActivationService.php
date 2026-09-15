<?php

namespace App\Services;

use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentActivationService
{
    public function activate(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            if ($payment->status === 'paid') {
                return $payment->fresh(['user']);
            }

            $user = User::query()->lockForUpdate()->findOrFail($payment->user_id);
            $plan = $payment->plan;

            if ($user->plan === 'premium' && $plan === 'starter') {
                // déjà premium : ne pas rétrograder
            } elseif ($plan === 'premium' || $user->plan !== 'premium') {
                $user->plan = $plan;
                $user->plan_activated_at = now();
                $user->save();
            }

            $payment->status = 'paid';
            $payment->paid_at = now();
            $payment->receipt_number = $payment->receipt_number ?: ('FL-'.strtoupper(Str::random(10)));
            $payment->save();

            Mail::to($user->email)->send(new PaymentReceiptMail($payment->fresh(['user'])));

            return $payment->fresh(['user']);
        });
    }

    public function createPending(User $user, string $plan, string $provider, ?string $externalId = null, array $payload = []): Payment
    {
        $config = config('foodlab.plans.'.$plan);
        if (! $config) {
            throw new \InvalidArgumentException('Plan invalide');
        }

        return Payment::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'plan' => $plan,
            'amount' => $config['price'],
            'currency' => $config['currency'],
            'status' => 'pending',
            'external_id' => $externalId,
            'payload' => $payload,
        ]);
    }
}
