<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_testimonials_index(): void
    {
        $admin = $this->getAdminUser();

        Testimonial::create([
            'name' => 'Fulan Pengulas',
            'content' => 'Kopi mantap dan tempat nyaman sekali.',
            'rating' => 5,
            'is_pinned' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.testimonials.index'));

        $response->assertStatus(200);
        $response->assertSee('Ulasan &amp; Reputasi Kafe', false);
        $response->assertSee('Sedang Di-Pin');
        $response->assertSee('Fulan Pengulas');
        // Admin view is curation only: NO "+ Tambah Ulasan" form
        $response->assertDontSee('Tambah Ulasan Pelanggan Baru');
    }

    public function test_admin_can_toggle_pin_status(): void
    {
        $admin = $this->getAdminUser();

        $testi = Testimonial::create([
            'name' => 'Budi Santoso',
            'content' => 'Rekomendasi tempat santai di sore hari.',
            'rating' => 5,
            'is_pinned' => false,
            'is_active' => true,
        ]);

        // 1. Toggle Pin ON
        $response = $this->actingAs($admin)->patch(route('admin.testimonials.toggle-pin', $testi->id));
        $response->assertSessionHas('success');

        $testi->refresh();
        $this->assertTrue($testi->is_pinned);

        // 2. Toggle Pin OFF via AJAX
        $ajaxResponse = $this->actingAs($admin)->patchJson(route('admin.testimonials.toggle-pin', $testi->id));
        $ajaxResponse->assertJson([
            'success' => true,
            'is_pinned' => false,
        ]);

        $testi->refresh();
        $this->assertFalse($testi->is_pinned);
    }

    public function test_admin_can_toggle_active_status_and_it_auto_unpins_if_deactivated(): void
    {
        $admin = $this->getAdminUser();

        $testi = Testimonial::create([
            'name' => 'Dewi Lestari',
            'content' => 'Pelayanan ramah sekali.',
            'rating' => 5,
            'is_pinned' => true,
            'is_active' => true,
        ]);

        // Deactivate (Hide)
        $this->actingAs($admin)->patch(route('admin.testimonials.toggle-active', $testi->id));

        $testi->refresh();
        $this->assertFalse($testi->is_active);
        // Must automatically be unpinned when hidden
        $this->assertFalse($testi->is_pinned);
    }

    public function test_admin_can_delete_inappropriate_testimonial(): void
    {
        $admin = $this->getAdminUser();

        $testi = Testimonial::create([
            'name' => 'Spammer',
            'content' => 'Konten spam yang tidak pantas.',
            'rating' => 1,
            'is_pinned' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.testimonials.destroy', $testi->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('testimonials', ['id' => $testi->id]);
    }

    public function test_customer_review_submission_defaults_to_unpinned(): void
    {
        $payload = [
            'name' => 'Pelanggan Baru',
            'handle' => '@pelanggan_baru',
            'order_tag' => 'Palm Sugar Latte',
            'rating' => 5,
            'content' => 'Pertama kali ke sini dan sangat terkesan dengan baristanya.',
        ];

        $response = $this->post(route('nucomu.review.store'), $payload);
        $response->assertSessionHas('review_success');

        $saved = Testimonial::where('name', 'Pelanggan Baru')->first();
        $this->assertNotNull($saved);
        $this->assertSame(5, $saved->rating);
        $this->assertTrue($saved->is_active);
        // Must be unpinned by default, waiting for admin curation
        $this->assertFalse($saved->is_pinned);
    }

    public function test_customer_web_only_shows_pinned_reviews(): void
    {
        // 1 pinned review
        Testimonial::create([
            'name' => 'Pengulas Terbaik',
            'content' => 'Banoffee terenak di Jawa Timur!',
            'rating' => 5,
            'is_pinned' => true,
            'is_active' => true,
        ]);

        // 1 unpinned review
        Testimonial::create([
            'name' => 'Pengulas Biasa',
            'content' => 'Tempatnya lumayan bagus.',
            'rating' => 4,
            'is_pinned' => false,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Banoffee terenak di Jawa Timur!');
        $response->assertDontSee('Tempatnya lumayan bagus.');
    }

    public function test_customer_web_renders_fallback_when_no_reviews_pinned(): void
    {
        // No pinned reviews in DB
        Testimonial::query()->update(['is_pinned' => false]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Belum Ada Ulasan yang Disematkan');
        $response->assertSee('Tulis Ulasan Anda');
    }
}
