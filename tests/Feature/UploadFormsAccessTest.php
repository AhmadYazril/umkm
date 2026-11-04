<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Menu;
use App\Models\User;
use App\Support\FileUploadHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UploadFormsAccessTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_file_upload_helper_returns_all_expected_keys(): void
    {
        $limits = FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto banner');

        $this->assertArrayHasKey('formatted', $limits);
        $this->assertArrayHasKey('limit_formatted', $limits);
        $this->assertArrayHasKey('effective_formatted', $limits);
        $this->assertArrayHasKey('raw', $limits);
        $this->assertArrayHasKey('bytes', $limits);
        $this->assertArrayHasKey('effective_bytes', $limits);
        $this->assertArrayHasKey('limit_bytes', $limits);
        $this->assertArrayHasKey('limit_kb', $limits);
        $this->assertArrayHasKey('limit_mb', $limits);
        $this->assertArrayHasKey('is_throttled', $limits);
        $this->assertArrayHasKey('is_limited_by_server', $limits);
        $this->assertArrayHasKey('server_limit_formatted', $limits);
        $this->assertArrayHasKey('app_limit_formatted', $limits);
        $this->assertArrayHasKey('guide_text', $limits);

        $this->assertNotEmpty($limits['formatted']);
        $this->assertGreaterThan(0, $limits['effective_bytes']);
    }

    public function test_admin_can_access_banner_create_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.banners.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Banner Halaman Depan');
    }

    public function test_admin_can_access_banner_edit_page(): void
    {
        $admin = $this->getAdminUser();

        $banner = Banner::create([
            'title' => 'Banner Uji Coba',
            'layout_mode' => 'poster',
            'media_type' => 'image',
            'media_path' => 'banner_uji.jpg',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(2),
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.banners.edit', $banner));

        $response->assertStatus(200);
        $response->assertSee('Edit Banner');
    }

    public function test_admin_can_access_about_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.about.index'));

        $response->assertStatus(200);
        $response->assertSee('Tentang Cafe');
    }

    public function test_admin_can_access_facilities_create_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.facilities.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Fasilitas Baru');
    }

    public function test_admin_can_access_facilities_edit_page(): void
    {
        $admin = $this->getAdminUser();

        $facility = Facility::create([
            'slug' => 'wifi-corner',
            'tab_name' => 'WiFi Corner',
            'title' => 'WiFi Corner Nyaman',
            'label' => 'WiFi Cepat',
            'description' => 'Tempat santai bekerja dan berselancar.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.facilities.edit', $facility));

        $response->assertStatus(200);
        $response->assertSee('Edit Fasilitas');
    }

    public function test_admin_can_access_gallery_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('Galeri');
    }

    public function test_admin_can_access_menu_create_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.menus.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Menu');
    }

    public function test_admin_can_access_menu_edit_page(): void
    {
        $admin = $this->getAdminUser();

        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'slug' => 'kopi-susu-gula-aren',
            'price' => 20000,
            'is_available' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.menus.edit', $menu));

        $response->assertStatus(200);
        $response->assertSee('Edit Menu');
    }

    public function test_admin_can_access_profile_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Profil Admin');
    }

    public function test_admin_can_access_settings_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
    }

    public function test_public_user_can_access_nucomu_page(): void
    {
        $response = $this->get(route('nucomu.index'));

        $response->assertStatus(200);
        $response->assertSee('Kirim Ulasan Sekarang');
    }

    public function test_public_user_can_access_reservation_page(): void
    {
        $response = $this->get(route('reservation'));

        $response->assertStatus(200);
        $response->assertSee('Reservasi Meja');
    }
}
