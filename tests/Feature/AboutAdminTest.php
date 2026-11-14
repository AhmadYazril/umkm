<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_access_about_page_with_friendly_indonesian_ui(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.about.index'));

        $response->assertStatus(200);

        // Header & help panel
        $response->assertSee('Tentang Cafe');
        $response->assertSee('? Petunjuk');
        $response->assertSee('Panduan Mengelola Halaman Tentang Cafe');

        // 2 main numbered steps and collapsible extra settings
        $response->assertSee('1. Foto atau video');
        $response->assertSee('2. Cerita cafe');
        $response->assertDontSee('3. Kalimat pilihan dan tombol');
        $response->assertSee('Pengaturan tambahan');
        $response->assertSee('Opsional. Biasanya tidak perlu diubah.');

        // Friendly field names
        $response->assertSee('Judul besar');
        $response->assertSee('Cerita cafe (paragraf 1)');
        $response->assertSee('Cerita cafe (paragraf 2)');
        $response->assertSee('Judul kecil di atas judul');
        $response->assertSee('Kalimat pilihan (tampil di kotak bawah)');
        $response->assertSee('Tulisan di tombol');
        $response->assertSee('Tombol mengarah ke');

        // Reset to default link
        $response->assertSee('Kembalikan ke teks bawaan');

        // Dropdown options
        $response->assertSee('Daftar menu');
        $response->assertSee('Reservasi meja');
        $response->assertSee('Fasilitas kafe');
        $response->assertSee('Ulasan pelanggan');
        $response->assertSee('Kontak &amp; lokasi', false);
        $response->assertSee('Alamat web lain');

        // Friendly preview header
        $response->assertSee('Contoh tampilan di website');
        $response->assertSee('Berubah saat Anda mengetik');

        // Form actions (non-sticky)
        $response->assertSee('Simpan perubahan');
        $response->assertSee('Batalkan');
        $response->assertSee('Ada perubahan yang belum disimpan');

        // Verify jargon is removed
        $response->assertDontSee('Modul 2 &bull; Nucomu Workspace Panel', false);
        $response->assertDontSee('LIVE PREVIEW');
        $response->assertDontSee('Real-time Sync');
        $response->assertDontSee('Lihat di Web Customer');
    }

    public function test_admin_can_update_about_content_successfully(): void
    {
        $admin = $this->getAdminUser();

        $payload = [
            'about_label' => 'CERITA KAMI / SEJARAH',
            'cafe_story_headline' => 'Kopi Hangat, Suasana Tenang di Tulungagung.',
            'cafe_story_p1' => 'Nucomu Cafe didirikan dengan cinta terhadap racikan kopi berkualitas dan kenyamanan bercengkerama.',
            'cafe_story_p2' => 'Setiap cangkir disajikan dengan biji kopi pilihan dan suasana tenang untuk melepas penat.',
            'about_quote' => 'Di sini, setiap tegukan adalah jeda yang menyegarkan pikiran Anda.',
            'about_button_text' => 'Lihat Menu Favorit',
            'about_button_link' => '#menu',
            'media_type' => 'video',
        ];

        $response = $this->actingAs($admin)->post(route('admin.about.update'), $payload);

        $response->assertRedirect(route('admin.about.index'));
        $response->assertSessionHas('success');

        // Verify settings in database
        $this->assertEquals('CERITA KAMI / SEJARAH', Setting::where('key', 'about_label')->value('value'));
        $this->assertEquals('Kopi Hangat, Suasana Tenang di Tulungagung.', Setting::where('key', 'cafe_story_headline')->value('value'));
        $this->assertEquals('Lihat Menu Favorit', Setting::where('key', 'about_button_text')->value('value'));
        $this->assertEquals('#menu', Setting::where('key', 'about_button_link')->value('value'));

        // Verify that web customer home reflects the updated about content
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('CERITA KAMI / SEJARAH');
        $homeResponse->assertSee('Kopi Hangat, Suasana Tenang di Tulungagung.');
        $homeResponse->assertDontSee('Lihat Menu Favorit');
    }

    public function test_admin_can_update_about_custom_link(): void
    {
        $admin = $this->getAdminUser();

        $payload = [
            'about_label' => 'OUR STORY / TENTANG KAMI',
            'cafe_story_headline' => 'Berawal dari Rasa, Kembali karena Rindu.',
            'cafe_story_p1' => 'Paragraf pertama cerita kafe...',
            'cafe_story_p2' => 'Paragraf kedua cerita kafe...',
            'about_quote' => 'Kutipan berkesan...',
            'about_button_text' => 'Kunjungi Instagram',
            'about_button_link' => 'https://instagram.com/nucomucafe',
            'media_type' => 'image',
        ];

        $response = $this->actingAs($admin)->post(route('admin.about.update'), $payload);

        $response->assertRedirect(route('admin.about.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('https://instagram.com/nucomucafe', Setting::where('key', 'about_button_link')->value('value'));
    }

    public function test_accordion_is_closed_by_default_when_using_default_values(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.about.index'));

        $response->assertStatus(200);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('id="accordionExtraContent"', false);
        $response->assertDontSee('id="extraModifiedBadge" 
                                          class="inline-flex', false);
    }

    public function test_accordion_auto_opens_and_shows_badge_when_extra_field_is_modified(): void
    {
        $admin = $this->getAdminUser();

        // Simpan nilai non-default untuk tombol
        Setting::create([
            'key' => 'about_button_text',
            'value' => 'Pesan Meja Sekarang',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.about.index'));

        $response->assertStatus(200);
        $response->assertSee('aria-expanded="true"', false);
        $response->assertSee('Sudah diubah');
    }

    public function test_accordion_auto_opens_when_validation_error_occurs_in_extra_fields(): void
    {
        $admin = $this->getAdminUser();

        // Kirim label kosong untuk memicu error validasi
        $response = $this->actingAs($admin)->post(route('admin.about.update'), [
            'about_label' => '',
            'cafe_story_headline' => 'Judul Valid',
            'cafe_story_p1' => 'Paragraf Valid',
            'media_type' => 'video',
        ]);

        $response->assertSessionHasErrors(['about_label']);

        // Follow redirect ke halaman form
        $followResponse = $this->actingAs($admin)->get(route('admin.about.index'));
        $followResponse->assertStatus(200);
    }
}
