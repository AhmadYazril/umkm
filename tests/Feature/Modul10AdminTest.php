<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\NotificationController;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Modul10AdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_admin_can_access_profile_page_and_update_name_email_and_avatar(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.profile.index'));
        $response->assertStatus(200);
        $response->assertSee('Profil &amp; Keamanan Akun', false);
        $response->assertSee('Foto Profil (Avatar)');
        $response->assertSee('Ganti Kata Sandi');

        $fakeAvatar = UploadedFile::fake()->image('my_avatar.jpg', 500, 500);

        $updateResponse = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => 'Admin Baru Nucomu',
            'email' => 'admin_baru@nucomu.com',
            'avatar' => $fakeAvatar,
        ]);

        $updateResponse->assertSessionHas('success');
        $updateResponse->assertRedirect();

        $admin->refresh();
        $this->assertEquals('Admin Baru Nucomu', $admin->name);
        $this->assertEquals('admin_baru@nucomu.com', $admin->email);
        $this->assertNotNull($admin->avatar);
        $this->assertFileExists(public_path('uploads/avatars/'.$admin->avatar));

        // Test remove avatar
        $removeResponse = $this->actingAs($admin)->post(route('admin.profile.removeAvatar'));
        $removeResponse->assertSessionHas('success');
        $admin->refresh();
        $this->assertNull($admin->avatar);
    }

    public function test_admin_can_update_password_with_valid_current_password(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.profile.password'), [
            'current_password' => 'password123',
            'password' => 'newpassword888',
            'password_confirmation' => 'newpassword888',
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $admin->refresh();
        $this->assertTrue(Hash::check('newpassword888', $admin->password));
    }

    public function test_admin_password_update_fails_with_invalid_current_password(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword888',
            'password_confirmation' => 'newpassword888',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $admin->refresh();
        $this->assertTrue(Hash::check('password123', $admin->password));
    }

    public function test_admin_can_access_notifications_and_see_gathered_events(): void
    {
        $admin = $this->getAdminUser();

        // 1. Expiring banner (expires in 15 minutes)
        Banner::create([
            'title' => 'Promo Kopi Sore Diskon 20%',
            'media_path' => 'promo_sore.jpg',
            'media_type' => 'image',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addMinutes(15),
            'is_active' => true,
        ]);

        // 2. Pending reservation
        Reservation::create([
            'name' => 'Fulan Pengunjung',
            'phone' => '081234567890',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '15:00',
            'guests' => 3,
            'status' => 'pending',
        ]);

        // 3. Pending order
        Order::create([
            'code' => 'NCM-TEST-1001',
            'customer_name' => 'Siti Pembeli',
            'customer_phone' => '085712345678',
            'order_type' => 'dine_in',
            'subtotal' => 45000,
            'total' => 45000,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        // 4. Unpinned review
        Testimonial::create([
            'name' => 'Bambang Pengulas',
            'content' => 'Tempatnya estetik dan kopinya pas.',
            'rating' => 5,
            'is_pinned' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.notifications.index'));
        $response->assertStatus(200);
        $response->assertSee('Pusat Notifikasi Kafe');
        $response->assertSee('Promo Kopi Sore Diskon 20%');
        $response->assertSee('Fulan Pengunjung');
        $response->assertSee('NCM-TEST-1001');
        $response->assertSee('Bambang Pengulas');

        // Test toggle read
        $key = 'res_'.Reservation::first()->id;
        $toggleResp = $this->actingAs($admin)->post(route('admin.notifications.toggleRead'), [
            'key' => $key,
        ]);
        $toggleResp->assertSessionHas('success');
        $this->assertContains($key, NotificationController::getReadKeys());

        // Test mark all read
        $markAllResp = $this->actingAs($admin)->post(route('admin.notifications.markAllRead'));
        $markAllResp->assertSessionHas('success');
        $this->assertEquals(0, NotificationController::getUnreadCount());
    }

    public function test_admin_can_access_help_guide_page_and_alias(): void
    {
        $admin = $this->getAdminUser();

        $response1 = $this->actingAs($admin)->get(route('admin.help.index'));
        $response1->assertStatus(200);
        $response1->assertSee('Panduan Pemakaian Admin');
        $response1->assertSee('Standar Rekomendasi Ukuran Foto &amp; Media', false);
        $response1->assertSee('Tanya Jawab Umum (FAQ)');
        $response1->assertSee('Mengapa banner promo otomatis hilang dari beranda website?');
        $response1->assertSee('Bagaimana cara mengganti nomor WhatsApp resmi kafe untuk reservasi &amp; kontak?', false);

        // Alias /admin/bantuan
        $response2 = $this->actingAs($admin)->get('/admin/bantuan');
        $response2->assertStatus(200);
    }

    public function test_admin_can_access_and_update_general_settings(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Umum Kafe');
        $response->assertSee('Nama Kafe Resmi');
        $response->assertSee('Pintasan Pengaturan Terpusat');

        $fakeLogo = UploadedFile::fake()->image('logo_cafe.png', 300, 100);
        $fakeFavicon = UploadedFile::fake()->image('favicon.png', 64, 64);

        $updateResp = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'cafe_name' => 'Nucomu Roastery & Eatery',
            'cafe_tagline' => 'Sajian Kopi Terbaik di Tulungagung',
            'cafe_subtitle' => 'SLOW HOSPITALITY',
            'cafe_logo' => $fakeLogo,
            'cafe_favicon' => $fakeFavicon,
        ]);

        $updateResp->assertSessionHas('success');
        $this->assertEquals('Nucomu Roastery & Eatery', Setting::get('cafe_name'));
        $this->assertEquals('Sajian Kopi Terbaik di Tulungagung', Setting::get('cafe_tagline'));
        $this->assertNotNull(Setting::get('cafe_logo'));
        $this->assertNotNull(Setting::get('cafe_favicon'));
    }

    public function test_notification_polling_endpoint_returns_json_counts(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.notifications.poll'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_total',
            'unread_messages',
            'unread_system',
        ]);
    }

    public function test_admin_layout_renders_header_bell_and_does_not_render_floating_trigger(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Header Bell & Dropdown container must be present
        $response->assertSee('adminNotifDropdownContainer');
        $response->assertSee('adminNotifDropdownBtn');
        $response->assertSee('adminNotifDropdownMenu');
        $response->assertSee('tabBtnPesan');
        $response->assertSee('tabBtnNotif');
        $response->assertSee('Tandai Semua Dilihat');

        // Floating bottom-right button and its modal must be completely absent
        $response->assertDontSee('btnGlobalMessagePopup');
        $response->assertDontSee('adminMessagePopupDropdown');
        $response->assertDontSee('adminMessageBackdrop');
    }
}
