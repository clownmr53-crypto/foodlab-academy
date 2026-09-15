<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentActivationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentActivationServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_activates_plan_and_sends_receipt(): void
    {
        Mail::fake();
        $user = User::factory()->create(['plan' => null]);
        $payment = Payment::create([
            'user_id' => $user->id,
            'provider' => 'fake',
            'plan' => 'starter',
            'amount' => 25000,
            'currency' => 'XOF',
            'status' => 'pending',
        ]);

        $service = app(PaymentActivationService::class);
        $activated = $service->activate($payment);

        $this->assertSame('paid', $activated->status);
        $this->assertNotNull($activated->receipt_number);
        $this->assertSame('starter', $user->fresh()->plan);
        Mail::assertSent(PaymentReceiptMail::class);
    }

    #[Test]
    public function it_upgrades_starter_to_premium(): void
    {
        Mail::fake();
        $user = User::factory()->starter()->create();
        $payment = Payment::create([
            'user_id' => $user->id,
            'provider' => 'fake',
            'plan' => 'premium',
            'amount' => 75000,
            'currency' => 'XOF',
            'status' => 'pending',
        ]);

        app(PaymentActivationService::class)->activate($payment);

        $this->assertSame('premium', $user->fresh()->plan);
    }
}
