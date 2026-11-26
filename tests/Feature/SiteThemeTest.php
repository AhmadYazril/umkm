<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteThemeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::resetThemeCache();
    }

    private function createUserWithRole(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    public function test_default_theme_is_light_and_public_endpoint_is_read_only(): void
    {
        $response = $this->getJson(route('api.site-settings.theme'));

        $response->assertOk()
            ->assertExactJson([
                'theme' => Setting::THEME_LIGHT,
            ]);

        $this->assertStringContainsString(
            'no-store',
            strtolower((string) $response->headers->get('Cache-Control'))
        );

        $this->postJson('/api/site-settings/theme', ['theme' => 'dark'])
            ->assertStatus(405);

        $this->putJson('/api/site-settings/theme', ['theme' => 'dark'])
            ->assertStatus(405);
    }

    public function test_public_landing_page_renders_light_theme_ssr_without_visitor_toggle(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('data-theme="light"', false);
        $response->assertSee('window.NUCOMU_SITE_THEME = "light";', false);
        $response->assertDontSee('class="scroll-smooth dark"', false);
        $response->assertDontSee('siteThemeForm', false);
        $response->assertDontSee('prefers-color-scheme', false);
        $response->assertDontSee("localStorage.getItem('theme')", false);
    }

    public function test_guest_cannot_update_site_theme(): void
    {
        $this->putJson(route('admin.settings.theme.update'), [
            'theme' => Setting::THEME_DARK,
        ])->assertStatus(401);

        $this->assertSame(Setting::THEME_LIGHT, Setting::getSiteTheme(true));

        $this->put(route('admin.settings.theme.update'), [
            'theme' => Setting::THEME_DARK,
        ])->assertRedirect(route('admin.login'));

        $this->assertSame(Setting::THEME_LIGHT, Setting::getSiteTheme(true));
    }

    public function test_non_admin_users_are_rejected_when_updating_site_theme(): void
    {
        foreach (['kasir', 'customer'] as $role) {
            $user = $this->createUserWithRole($role);

            $this->actingAs($user)
                ->putJson(route('admin.settings.theme.update'), [
                    'theme' => Setting::THEME_DARK,
                ])
                ->assertForbidden();

            $this->actingAs($user)
                ->post(route('admin.settings.theme.update'), [
                    'theme' => Setting::THEME_DARK,
                ])
                ->assertForbidden();
        }

        $this->assertSame(Setting::THEME_LIGHT, Setting::getSiteTheme(true));
    }

    public function test_admin_theme_update_rejects_invalid_values(): void
    {
        $admin = $this->createUserWithRole('admin');

        foreach (['blue', '', 'system', 'DARK_MODE', '123'] as $invalidTheme) {
            $this->actingAs($admin)
                ->putJson(route('admin.settings.theme.update'), [
                    'theme' => $invalidTheme,
                ])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['theme']);
        }

        $this->assertSame(Setting::THEME_LIGHT, Setting::getSiteTheme(true));
    }

    public function test_admin_can_switch_theme_to_dark_and_back_to_light_globally(): void
    {
        $admin = $this->createUserWithRole('admin');

        // 1. Switch to dark via JSON API (used by Admin Settings UI)
        $response = $this->actingAs($admin)
            ->putJson(route('admin.settings.theme.update'), [
                'theme' => Setting::THEME_DARK,
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'theme' => Setting::THEME_DARK,
            ]);

        $this->assertDatabaseHas('settings', [
            'key' => Setting::THEME_KEY,
            'value' => Setting::THEME_DARK,
        ]);

        // 2. Public API & public SSR pages immediately reflect dark theme
        $this->getJson(route('api.site-settings.theme'))
            ->assertOk()
            ->assertExactJson(['theme' => Setting::THEME_DARK]);

        foreach ([
            route('home'),
            route('menu.all'),
            route('gallery'),
            route('contact'),
            route('services'),
            route('reservation'),
            route('nucomu.index'),
        ] as $url) {
            $page = $this->get($url);
            $page->assertOk();
            $page->assertSee('data-theme="dark"', false);
            $page->assertSee('dark', false);
        }

        // 3. Switch back to light via standard form POST (non-JS fallback)
        $webResponse = $this->actingAs($admin)
            ->from(route('admin.settings.index'))
            ->post(route('admin.settings.theme.update'), [
                'theme' => Setting::THEME_LIGHT,
            ]);

        $webResponse->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => Setting::THEME_KEY,
            'value' => Setting::THEME_LIGHT,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-theme="light"', false)
            ->assertDontSee('class="scroll-smooth dark"', false);

        // 4. General settings endpoint also accepts valid theme update
        $this->actingAs($admin)
            ->post(route('admin.settings.update'), [
                'theme' => Setting::THEME_DARK,
            ])
            ->assertSessionHas('success');

        $this->assertSame(Setting::THEME_DARK, Setting::getSiteTheme(true));
    }

    public function test_admin_settings_page_displays_theme_selector_and_preview(): void
    {
        $admin = $this->createUserWithRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Tema Tampilan Landing Page');
        $response->assertSee('id="siteThemeForm"', false);
        $response->assertSee('id="siteThemePreviewBox"', false);
        $response->assertSee('value="light"', false);
        $response->assertSee('value="dark"', false);
    }
}
