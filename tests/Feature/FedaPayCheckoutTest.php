<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\FedaPayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FedaPayCheckoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function checkout_falls_back_to_simulate_when_fedapay_keys_missing(): void
    {
        Mail::fake();
        config([
            'foodlab.payments.fedapay.secret_key' => null,
            'foodlab.payments.fedapay.public_key' => null,
            'foodlab.payments.default_mm_provider' => 'fedapay',
            'foodlab.plans.premium.price' => 149000,
        ]);

        $user = User::factory()->create(['plan' => null, 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->post(route('payments.checkout'), [
            'plan' => 'premium',
            'provider' => 'fedapay',
        ]);

        $response->assertOk();
        $response->assertSee('simulation', false);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'provider' => 'fedapay',
            'plan' => 'premium',
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function checkout_redirects_to_fedapay_url_when_configured(): void
    {
        Mail::fake();
        config([
            'foodlab.payments.fedapay.secret_key' => 'sk_sandbox_test',
            'foodlab.payments.fedapay.public_key' => 'pk_sandbox_test',
            'foodlab.payments.fedapay.mode' => 'sandbox',
            'foodlab.payments.default_mm_provider' => 'fedapay',
            'foodlab.plans.premium.price' => 149000,
        ]);

        $mock = Mockery::mock(FedaPayService::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('createCheckout')->once()->andReturn([
            'transaction_id' => '99901',
            'url' => 'https://sandbox.fedapay.com/checkout/test-token',
            'token' => 'test-token',
        ]);
        $this->app->instance(FedaPayService::class, $mock);

        $user = User::factory()->create(['plan' => null, 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->post(route('payments.checkout'), [
            'plan' => 'premium',
            'provider' => 'fedapay',
        ]);

        $response->assertRedirect('https://sandbox.fedapay.com/checkout/test-token');
        $payment = Payment::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($payment);
        $this->assertSame('99901', $payment->external_id);
        $this->assertSame('pending', $payment->status);
    }

    #[Test]
    public function webhook_activates_plan_on_transaction_approved_without_real_api(): void
    {
        Mail::fake();
        config([
            'foodlab.payments.default_mm_provider' => 'fedapay',
            'foodlab.payments.fedapay.webhook_secret' => null,
        ]);

        $user = User::factory()->create(['plan' => null, 'email_verified_at' => now()]);
        $payment = Payment::create([
            'user_id' => $user->id,
            'provider' => 'fedapay',
            'plan' => 'premium',
            'amount' => 149000,
            'currency' => 'XOF',
            'status' => 'pending',
            'external_id' => '555',
        ]);

        $payload = [
            'name' => 'transaction.approved',
            'entity' => [
                'id' => 555,
                'status' => 'approved',
                'amount' => 149000,
                'custom_metadata' => [
                    'payment_id' => (string) $payment->id,
                    'plan' => 'premium',
                ],
            ],
        ];

        $response = $this->postJson(route('webhooks.mm'), $payload);
        $response->assertOk()->assertJson(['ok' => true]);

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame('premium', $user->fresh()->plan);
    }

    #[Test]
    public function fedapay_callback_activates_when_status_approved(): void
    {
        Mail::fake();
        $user = User::factory()->create(['plan' => 'starter', 'email_verified_at' => now()]);
        $payment = Payment::create([
            'user_id' => $user->id,
            'provider' => 'fedapay',
            'plan' => 'premium',
            'amount' => 149000,
            'currency' => 'XOF',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('payments.fedapay.callback', $payment).'?id=888&status=approved');
        $response->assertRedirect(route('payments.success', $payment));

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame('premium', $user->fresh()->plan);
        $this->assertSame('888', $payment->fresh()->external_id);
    }

    #[Test]
    public function plans_page_prefers_fedapay_provider(): void
    {
        config(['foodlab.payments.default_mm_provider' => 'fedapay']);
        $user = User::factory()->create(['plan' => null, 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('payments.plans'));
        $response->assertOk();
        $response->assertSee('FedaPay', false);
        $response->assertSee('MTN', false);
        $response->assertSee('Orange', false);
        $response->assertSee('Moov', false);
        $response->assertSee('Wave', false);
    }
}
