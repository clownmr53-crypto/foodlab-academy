<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentActivationService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterPayLmsFlowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function register_pay_then_access_lms(): void
    {
        Mail::fake();

        $module = Module::create([
            'title' => 'Bases',
            'slug' => 'bases',
            'order' => 1,
            'is_premium_only' => false,
            'is_published' => true,
        ]);
        Lesson::create([
            'module_id' => $module->id,
            'title' => 'Intro',
            'slug' => 'intro',
            'order' => 1,
            'is_published' => true,
            'video_provider' => 'bunny',
            'video_id' => 'abc',
        ]);
        Module::create([
            'title' => 'Premium Mod',
            'slug' => 'prem',
            'order' => 4,
            'is_premium_only' => true,
            'is_published' => true,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'flow@foodlab.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'country' => 'Bénin',
            'sector' => 'Restauration',
            'level' => 'débutant',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'flow@foodlab.test')->firstOrFail();
        $user->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($user->fresh());

        $this->get(route('lms.index'))->assertRedirect(route('payments.plans'));

        $checkout = $this->post(route('payments.checkout'), [
            'plan' => 'starter',
            'provider' => 'fake',
        ]);
        // Starter à 0 FCFA : activation immédiate
        $checkout->assertRedirect();

        $this->assertSame('starter', $user->fresh()->plan);
        $payment = Payment::where('user_id', $user->id)->latest()->firstOrFail();
        $this->assertSame('paid', $payment->status);
        $this->actingAs($user->fresh());

        $this->get(route('lms.index'))->assertOk();
        $this->get(route('lms.module', $module))->assertOk();

        $premium = Module::where('slug', 'prem')->first();
        $this->get(route('lms.module', $premium))->assertRedirect(route('payments.plans'));
    }
}
