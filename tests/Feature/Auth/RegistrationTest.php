<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        // phpunit uses MAIL_MAILER=array → auto-verify by default
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_validation_lowercase_message_is_translated_in_french(): void
    {
        app()->setLocale('fr');

        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'NotLower@Example.COM',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $message = session('errors')->first('email');
        $this->assertStringNotContainsString('validation.lowercase', $message);
        $this->assertStringContainsString('minuscules', $message);
    }
}
