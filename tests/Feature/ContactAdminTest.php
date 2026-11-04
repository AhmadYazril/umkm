<?php

namespace Tests\Feature;

use App\Http\Controllers\NucomuController;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_access_contact_page_and_alias_kontak(): void
    {
        $admin = $this->getAdminUser();

        $response1 = $this->actingAs($admin)->get(route('admin.contact.index'));
        $response1->assertStatus(200);
        $response1->assertSee('Pengaturan Kontak &amp; Lokasi', false);
        $response1->assertSee('Nomor WhatsApp Cafe Resmi');
        $response1->assertSee('Nomor WhatsApp Kurir Zendo');
        $response1->assertSee('Tautan Outlet GrabFood');
        $response1->assertSee('Tautan Instagram');
        $response1->assertSee('Tautan TikTok');
        $response1->assertSee('Alamat Lengkap Cafe');

        // Test alias route /admin/kontak
        $response2 = $this->actingAs($admin)->get('/admin/kontak');
        $response2->assertStatus(200);
    }

    public function test_contact_page_prefills_existing_settings(): void
    {
        $admin = $this->getAdminUser();

        Setting::set('cafe_whatsapp', '6281122334455');
        Setting::set('cafe_address', 'Jl. Sukarno Hatta No. 99, Tulungagung');
        Setting::set('cafe_instagram', 'https://www.instagram.com/nucomucafe_custom');

        $response = $this->actingAs($admin)->get(route('admin.contact.index'));
        $response->assertStatus(200);
        $response->assertSee('6281122334455');
        $response->assertSee('Jl. Sukarno Hatta No. 99, Tulungagung');
        $response->assertSee('https://www.instagram.com/nucomucafe_custom');
    }

    public function test_admin_can_update_contact_with_auto_normalization_and_iframe_extraction(): void
    {
        $admin = $this->getAdminUser();

        $payload = [
            'cafe_whatsapp' => '081234567890', // Starts with 08, must be normalized to 6281234567890
            'zendo_whatsapp' => '085707300240', // Starts with 08, must be normalized to 6285707300240
            'grabfood_url' => 'https://food.grab.com/id/id/restaurant/nucomu-cafe',
            'cafe_instagram' => '@nucomu.official', // Username format auto converted to URL
            'cafe_tiktok' => '@nucomu.tiktok',
            'cafe_email' => 'halo@nucomucafe.com',
            'cafe_address' => 'Jl. Mayor Sujadi No. 77, Tulungagung',
            'cafe_maps_url' => 'https://maps.app.goo.gl/example123',
            'cafe_maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000" width="600" height="450"></iframe>',
            'cafe_opening_hours' => 'Senin - Minggu: 10:00 - 22:00 WIB',
        ];

        $response = $this->actingAs($admin)->post(route('admin.contact.update'), $payload);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $this->assertEquals('6281234567890', Setting::get('cafe_whatsapp'));
        $this->assertEquals('6285707300240', Setting::get('zendo_whatsapp'));
        $this->assertEquals('6285707300240', Setting::get('zendo_courier_1_phone'));
        $this->assertEquals('https://food.grab.com/id/id/restaurant/nucomu-cafe', Setting::get('grabfood_url'));
        $this->assertEquals('https://www.instagram.com/nucomu.official', Setting::get('cafe_instagram'));
        $this->assertEquals('https://www.tiktok.com/@nucomu.tiktok', Setting::get('cafe_tiktok'));
        $this->assertEquals('halo@nucomucafe.com', Setting::get('cafe_email'));
        $this->assertEquals('Jl. Mayor Sujadi No. 77, Tulungagung', Setting::get('cafe_address'));
        $this->assertEquals('https://maps.app.goo.gl/example123', Setting::get('cafe_maps_url'));
        $this->assertEquals('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1000', Setting::get('cafe_maps_embed'));
        $this->assertEquals('Senin - Minggu: 10:00 - 22:00 WIB', Setting::get('cafe_opening_hours'));
    }

    public function test_invalid_whatsapp_number_is_rejected_with_indonesian_error(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.contact.update'), [
            'cafe_whatsapp' => '123456', // Invalid format
        ]);

        $response->assertSessionHasErrors(['cafe_whatsapp']);
    }

    public function test_empty_social_link_hides_icon_on_website(): void
    {
        // Set social link empty
        Setting::set('cafe_instagram', '');
        Setting::set('cafe_tiktok', '');
        Setting::set('grabfood_url', '');
        Setting::set('cafe_email', '');

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Icons with labels should NOT appear in footer when empty
        $response->assertDontSee('aria-label="Instagram Nucomu Cafe"', false);
        $response->assertDontSee('aria-label="TikTok Nucomu Cafe"', false);
        $response->assertDontSee('aria-label="GrabFood Nucomu Cafe"', false);
    }

    public function test_shared_keys_grabfood_and_zendo_sync_with_transaction_settings(): void
    {
        $admin = $this->getAdminUser();

        // 1. Update through contact admin
        $this->actingAs($admin)->post(route('admin.contact.update'), [
            'cafe_whatsapp' => '081234567890',
            'zendo_whatsapp' => '089988776655',
            'grabfood_url' => 'https://food.grab.com/id/id/restaurant/nucomu-sync-test',
        ]);

        // 2. NucomuController getTransactionSettings should immediately return the synced data
        $tx = (new NucomuController)->getTransactionSettings();
        $this->assertEquals('https://food.grab.com/id/id/restaurant/nucomu-sync-test', $tx['grabfood_url']);
        $this->assertEquals('6289988776655', $tx['zendo_whatsapp_clean']);

        // 3. NucomuController getContactSettings should also return the synced data
        $contact = NucomuController::getContactSettings();
        $this->assertEquals('https://food.grab.com/id/id/restaurant/nucomu-sync-test', $contact['grabfood_url']);
        $this->assertEquals('6289988776655', $contact['zendo_whatsapp']);
    }
}
