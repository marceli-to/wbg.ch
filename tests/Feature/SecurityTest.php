<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_removed_maintenance_and_newsletter_routes_are_gone(): void
    {
        foreach (['/artisan/cache', '/artisan/config', '/artisan/view', '/artisan/symlink', '/newsletter/send'] as $uri) {
            $this->get($uri)->assertNotFound();
        }
    }

    public function test_admin_api_requires_authentication(): void
    {
        foreach (['/api/user', '/api/projects', '/api/team', '/api/home-grids'] as $uri) {
            $this->getJson($uri)->assertUnauthorized();
        }

        $this->postJson('/api/uploads/image')->assertUnauthorized();
        $this->deleteJson('/api/projects/1')->assertUnauthorized();
    }

    public function test_login_and_logout_with_session(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertOk()
            ->assertJsonPath('email', $user->email)
            ->assertJsonMissingPath('password');

        $this->getJson('/api/user')->assertOk()->assertJsonPath('id', $user->id);

        $this->postJson('/api/logout')->assertNoContent();
        $this->assertGuest('web');
    }

    public function test_login_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])->assertUnprocessable();
        }

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_media_routes_reject_paths(): void
    {
        $this->get('/media/..%2F..%2F.env/sm')->assertNotFound();
        $this->get('/media/source/.htaccess')->assertNotFound();
        $this->get('/media/does-not-exist.jpg/sm')->assertNotFound();
        // Re-processing the source through a public URL is no longer possible
        $this->get('/media/anything.jpg/source')->assertNotFound();
    }

    public function test_security_headers_are_set(): void
    {
        $this->get('/agb')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
