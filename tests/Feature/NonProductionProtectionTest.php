<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NonProductionProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_auth_is_required_when_configured(): void
    {
        config(['app.basic_auth.user' => 'wbg', 'app.basic_auth.password' => 'secret']);

        $this->get('/agb')
            ->assertUnauthorized()
            ->assertHeader('WWW-Authenticate');

        $this->withHeaders(['Authorization' => 'Basic '.base64_encode('wbg:wrong')])
            ->get('/agb')
            ->assertUnauthorized();

        $this->withHeaders(['Authorization' => 'Basic '.base64_encode('wbg:secret')])
            ->get('/agb')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertDontSee('googletagmanager');
    }

    public function test_no_basic_auth_without_credentials_but_still_noindex(): void
    {
        $this->get('/agb')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_production_is_public_indexable_and_tracked(): void
    {
        config(['app.basic_auth.user' => 'wbg', 'app.basic_auth.password' => 'secret']);
        $this->app['env'] = 'production';

        $this->get('/agb')
            ->assertOk()
            ->assertHeaderMissing('X-Robots-Tag')
            ->assertSee('googletagmanager');
    }
}
