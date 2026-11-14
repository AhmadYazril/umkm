<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BannerAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_access_banner_index_with_friendly_ui(): void
    {
        $admin = $this->getAdminUser();

        $banner = Banner::create([
            'title' => 'Promo Spesial Kopi Susu',
            'subtitle' => 'Diskon 20% &bull; Weekend',
            'description' => 'Promo spesial akhir pekan untuk semua varian kopi susu.',
            'media_type' => 'image',
            'media_path' => 'banner_test.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(3),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.banners.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Halaman Depan');
        $response->assertSee('Lihat Website');
        $response->assertSee('Tambah Banner Baru');
        // Check single plus: no redundant "+ Tambah Banner Baru"
        $response->assertDontSee('+ Tambah Banner Baru');

        // Check that raw &BULL; does NOT appear on screen
        $response->assertDontSee('&BULL;', false);
        $response->assertDontSee('&amp;bull;', false);

        // Check action button texts
        $response->assertSee('Edit');
        $response->assertSee('Perpanjang waktu');
        $response->assertSee('Matikan');
        $response->assertSee('Hapus');

        // Check friendly date and status
        $response->assertSee('Sedang Tampil');
        $response->assertSee('Tersisa 3 hari');
    }

    public function test_banner_schedule_human_and_remaining_human(): void
    {
        $start = Carbon::create(2026, 11, 2, 3, 6);
        $end = Carbon::create(2026, 11, 7, 5, 31);

        $banner = new Banner([
            'start_time' => $start,
            'end_time' => $end,
        ]);

        $this->assertEquals('Tampil 2 Nov, 03.06 sampai 7 Nov, 05.31', $banner->schedule_human);
    }

    public function test_compact_summary_bar_only_shows_expiring_chip_when_count_positive(): void
    {
        $admin = $this->getAdminUser();

        // 1. When NO banner is expiring soon
        Banner::create([
            'title' => 'Promo Panjang',
            'media_type' => 'image',
            'media_path' => 'banner1.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(5),
            'is_active' => true,
        ]);

        $response1 = $this->actingAs($admin)->get(route('admin.banners.index'));
        $response1->assertStatus(200);
        $response1->assertSee('Sedang Tampil (1)');
        // Expiring filter should not appear if expiring count is 0
        $response1->assertDontSee('Segera Berakhir');

        // 2. When a banner is expiring within 30 minutes
        Banner::create([
            'title' => 'Promo Kilat 15 Menit',
            'media_type' => 'image',
            'media_path' => 'banner2.jpg',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addMinutes(15),
            'is_active' => true,
        ]);

        $response2 = $this->actingAs($admin)->get(route('admin.banners.index'));
        $response2->assertStatus(200);
        $response2->assertSee('Sedang Tampil (2)');
        $response2->assertSee('Segera Berakhir (1)');
    }

    public function test_store_and_update_sanitizes_html_entities_in_subtitle(): void
    {
        $admin = $this->getAdminUser();

        // Test store
        $file = UploadedFile::fake()->image('banner_hero.jpg', 1920, 1080);
        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Spesial Weekend',
            'subtitle' => 'Nucomu Promo &bull; Tulungagung',
            'media_type' => 'image',
            'media_file' => $file,
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
            'is_active' => 1,
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.banners.index'));

        $banner = Banner::latest('id')->first();
        $this->assertNotNull($banner);
        $this->assertEquals('Nucomu Promo • Tulungagung', $banner->subtitle);

        // Test update
        $response2 = $this->actingAs($admin)->put(route('admin.banners.update', $banner), [
            'title' => 'Promo Weekend Update',
            'subtitle' => 'Special Roastery &bull; Best Blend',
            'media_type' => 'image',
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(4)->format('Y-m-d\TH:i'),
            'is_active' => 1,
            'sort_order' => 1,
        ]);

        $response2->assertRedirect(route('admin.banners.index'));
        $banner->refresh();
        $this->assertEquals('Special Roastery • Best Blend', $banner->subtitle);
    }

    public function test_admin_banner_index_and_forms_show_desktop_only_notice_and_mode_options(): void
    {
        $admin = $this->getAdminUser();

        $banner = Banner::create([
            'title' => 'Promo Diskon Kopi',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_path' => 'banner_test.jpg',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addDays(2),
            'is_active' => true,
        ]);

        // 1. Index page
        $resIndex = $this->actingAs($admin)->get(route('admin.banners.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Banner hanya tampil di layar komputer. Di HP banner tidak ditampilkan.');
        $resIndex->assertSee('Poster');

        // 2. Create form
        $resCreate = $this->actingAs($admin)->get(route('admin.banners.create'));
        $resCreate->assertStatus(200);
        $resCreate->assertSee('Banner hanya tampil di layar komputer. Di HP banner tidak ditampilkan.');
        $resCreate->assertSee('Mode Poster');
        $resCreate->assertSee('Teks di Atas Gambar');
        $resCreate->assertSee('Terbagi (Split)');
        $resCreate->assertSee('Pratinjau: Tampilan Komputer');

        // 3. Edit form
        $resEdit = $this->actingAs($admin)->get(route('admin.banners.edit', $banner));
        $resEdit->assertStatus(200);
        $resEdit->assertSee('Banner hanya tampil di layar komputer. Di HP banner tidak ditampilkan.');
        $resEdit->assertSee('Mode Poster');
        $resEdit->assertSee('Pratinjau: Tampilan Komputer');
    }

    public function test_upload_error_ini_size_returns_clear_indonesian_message(): void
    {
        $admin = $this->getAdminUser();

        $file = new UploadedFile(
            sys_get_temp_dir().'/fake_large_banner.jpg',
            'fake_large_banner.jpg',
            'image/jpeg',
            UPLOAD_ERR_INI_SIZE,
            true
        );

        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Spesial Melebihi Batas',
            'media_type' => 'image',
            'media_file' => $file,
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors('media_file');
        $errors = session('errors')->get('media_file');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('melebihi batas server PHP', $errors[0]);
    }

    public function test_upload_error_partial_returns_clear_indonesian_message(): void
    {
        $admin = $this->getAdminUser();

        $file = new UploadedFile(
            sys_get_temp_dir().'/fake_partial_banner.jpg',
            'fake_partial_banner.jpg',
            'image/jpeg',
            UPLOAD_ERR_PARTIAL,
            true
        );

        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Spesial File Terputus',
            'media_type' => 'image',
            'media_file' => $file,
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors('media_file');
        $errors = session('errors')->get('media_file');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('hanya terunggah sebagian', $errors[0]);
    }

    public function test_post_too_large_exception_returns_actionable_indonesian_message(): void
    {
        $exception = new PostTooLargeException;
        $request = Request::create(route('admin.banners.store'), 'POST');
        $request->setLaravelSession(session()->driver());

        $response = $this->app->make(ExceptionHandler::class)->render($request, $exception);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(session()->has('error'));
        $this->assertStringContainsString('Total ukuran berkas yang dikirim melebihi batas server PHP', session('error'));
    }

    public function test_store_validates_dimensions_and_persists_layout_mode(): void
    {
        $admin = $this->getAdminUser();

        // 1. Image width < 1600 should fail
        $smallFile = UploadedFile::fake()->image('small_banner.jpg', 1200, 600);
        $resFail = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Gambar Kecil',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_file' => $smallFile,
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
            'is_active' => 1,
        ]);
        $resFail->assertSessionHasErrors('media_file');

        // 2. Image portrait (height > width) should fail
        $portraitFile = UploadedFile::fake()->image('portrait_banner.jpg', 1600, 2400);
        $resFail2 = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Gambar Tegak',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_file' => $portraitFile,
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
            'is_active' => 1,
        ]);
        $resFail2->assertSessionHasErrors('media_file');

        // 3. Valid landscape 1920x1080 image with layout_mode 'poster' succeeds
        $validFile = UploadedFile::fake()->image('valid_banner.jpg', 1920, 1080);
        $resOk = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Promo Sukses Poster',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_file' => $validFile,
            'button_link' => '#menu-promo',
            'start_time' => Carbon::now()->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i'),
            'is_active' => 1,
        ]);
        $resOk->assertRedirect(route('admin.banners.index'));

        $saved = Banner::where('title', 'Promo Sukses Poster')->first();
        $this->assertNotNull($saved);
        $this->assertEquals('poster', $saved->layout_mode);
        $this->assertEquals('#menu-promo', $saved->button_link);
    }

    public function test_admin_can_access_banner_create_page_without_errors(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.banners.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Banner Halaman Depan');
        $response->assertSee('Panduan Ukuran');
        $response->assertSee('Maks');
    }

    public function test_admin_can_access_banner_edit_page_without_errors(): void
    {
        $admin = $this->getAdminUser();

        $banner = Banner::create([
            'title' => 'Banner Edit Test',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_path' => 'banner_test.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(2),
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.banners.edit', $banner));

        $response->assertStatus(200);
        $response->assertSee('Edit Banner');
        $response->assertSee('Panduan Ukuran');
    }

    public function test_customer_web_renders_desktop_banners_and_mobile_fallback_hero(): void
    {
        // Create active banner in poster mode
        Banner::create([
            'title' => 'Banner Promo Komputer Nucomu',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_path' => 'banner_desktop_test.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(3),
            'button_link' => '#menu',
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // Desktop wrapper contains banner and data-desktop-src
        $response->assertSee('id="desktop-hero-banners-wrapper"', false);
        $response->assertSee('data-desktop-src', false);
        $response->assertSee('Banner Promo Komputer Nucomu');

        // Mobile wrapper contains fallback native hero
        $response->assertSee('id="mobile-hero-fallback-wrapper"', false);
        $response->assertSee('Classic Italian');
        $response->assertSee('Tiramisu Nucomu');
        $response->assertSee('id="hero-navbar-offset"', false);
        $response->assertSee('navbar--over-hero navbar--flush-hero', false);
        $response->assertSee("document.documentElement.style.setProperty('--hero-bg-color', \"#0C0C0C\")", false);
    }

    public function test_overlay_banner_sets_dark_adaptive_navbar_theme_below_header(): void
    {
        Banner::create([
            'title' => 'Banner Overlay Gelap',
            'layout_mode' => 'overlay',
            'media_type' => 'image',
            'media_path' => 'banner_overlay_test.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(3),
            'button_link' => '#menu',
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="hero-navbar-offset"', false);
        $response->assertSee('navbar--over-hero navbar--flush-hero', false);
        $response->assertSee("document.documentElement.style.setProperty('--hero-bg-color', \"#0C0C0C\")", false);
        $response->assertSee('data-layout-mode="overlay"', false);
        $response->assertSee('data-theme="dark"', false);
    }
}
