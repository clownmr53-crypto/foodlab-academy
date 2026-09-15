<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    public function test_google_redirect_route_redirects_to_provider(): void
    {
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('scopes')->once()->andReturnSelf();
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('https://accounts.google.com/o/oauth2'));

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('auth.google'))
            ->assertRedirect('https://accounts.google.com/o/oauth2');
    }

    public function test_google_callback_creates_verified_user_and_logs_in(): void
    {
        $socialiteUser = (new SocialiteUser)->map([
            'id' => 'google-123',
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'avatar' => 'https://example.com/a.png',
        ]);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->once()->andReturn($socialiteUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'ada@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('google-123', $user->google_id);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_google_callback_links_existing_email_account(): void
    {
        $existing = User::factory()->create([
            'email' => 'ada@example.com',
            'google_id' => null,
            'email_verified_at' => null,
        ]);

        $socialiteUser = (new SocialiteUser)->map([
            'id' => 'google-456',
            'name' => 'Ada',
            'email' => 'ada@example.com',
        ]);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->once()->andReturn($socialiteUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('dashboard', absolute: false));

        $existing->refresh();
        $this->assertSame('google-456', $existing->google_id);
        $this->assertNotNull($existing->email_verified_at);
        $this->assertAuthenticatedAs($existing);
    }

    public function test_login_and_register_pages_show_google_button(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Continuer avec Google', false);
        $this->get(route('register'))->assertOk()->assertSee('inscrire avec Google');
    }
}
