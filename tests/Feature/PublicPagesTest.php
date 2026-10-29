<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('NUCOMU');
    }

    public function test_gallery_page_returns_successful_response_without_errors(): void
    {
        $response = $this->get('/galeri');
        $response->assertStatus(200);
        $response->assertSee('Galeri');
    }

    public function test_menu_page_returns_successful_response(): void
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
    }

    public function test_cart_page_returns_successful_response_with_session_cart(): void
    {
        $category = Category::create(['name' => 'Coffee', 'slug' => 'coffee']);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Banoffee',
            'slug' => 'banoffee',
            'price' => 25000,
            'is_available' => true,
        ]);

        $sessionCart = [
            '1_abc' => [
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'unit_price' => 25000,
                'qty' => 2,
                'options' => [],
                'line_total' => 50000,
            ],
        ];

        $response = $this->withSession(['cart' => $sessionCart])->get('/keranjang');
        $response->assertStatus(200);
        $response->assertSee('Banoffee');
    }

    public function test_home_page_renders_our_story_about_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('OUR STORY / TENTANG KAMI');
        $response->assertSee('Berawal dari Rasa,');
        $response->assertSee('Kembali karena Rindu.');
        $response->assertSee('Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.');
        $response->assertSee('Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.');
        $response->assertSee('Jelajahi Pilihan Menu');
        $response->assertSee('href="#menu"', false);
        $response->assertSee('id="menu"', false);
        $response->assertSee('Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.');
        $response->assertSee('nucomu_story_1.png');
        $response->assertSee('nucomu_logo_icon.png');
    }

    public function test_old_tentang_kami_route_no_longer_exists(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(404);
    }
}
