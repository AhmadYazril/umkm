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
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.testimonials.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Ulasan Pelanggan');
        $response->assertSee('Tambah Ulasan Pelanggan Baru');
        $response->assertSee('Fulan Pengulas');
    }

    public function test_admin_can_store_testimonial(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.testimonials.store'), [
            'name' => 'Budi Santoso',
            'handle' => 'budi_s',
            'order_tag' => 'Palm Sugar Latte',
            'rating' => 5,
            'content' => 'Rekomendasi tempat santai di sore hari.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Budi Santoso',
            'handle' => '@budi_s',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_active_status(): void
    {
        $admin = $this->getAdminUser();

        $testi = Testimonial::create([
            'name' => 'Dewi Lestari',
            'content' => 'Pelayanan ramah sekali.',
            'rating' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(route('admin.testimonials.toggle', $testi->id));

        $testi->refresh();
        $this->assertFalse($testi->is_active);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $admin = $this->getAdminUser();

        $testi = Testimonial::create([
            'name' => 'Spammer',
            'content' => 'Konten spam yang tidak pantas.',
            'rating' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.testimonials.destroy', $testi->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('testimonials', ['id' => $testi->id]);
    }

    public function test_customer_review_submission_appears_with_google_maps_reviews_on_web(): void
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

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Pelanggan Baru');
        $homeResponse->assertSee('Dya slma');
    }
}
