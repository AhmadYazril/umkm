<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
        $response->assertSee('bg-[#FFFFFF]', false);
        $response->assertDontSee('#F5F2EC', false);
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
        $response->assertDontSee('Jelajahi Pilihan Menu');
        $response->assertSee('id="menu"', false);
        $response->assertDontSee('id="story-quote-wrapper"', false);
        $response->assertDontSee('id="story-typewriter-text"', false);
        $response->assertDontSee('id="story-typewriter-cursor"', false);
        $response->assertDontSee('id="about-headline-cursor"', false);
        $response->assertDontSee('id="about-typing-cursor-1"', false);
        $response->assertDontSee('id="about-typing-cursor-2"', false);
        $response->assertSee('--color-about-bg: #FFFFFF', false);
        $response->assertSee('--color-about-backplate: #A5A5A5', false);
        $response->assertSee('--color-about-badge-dot: #242424', false);
        $response->assertSee('--color-about-bg: #0A0A0A', false);
        $response->assertSee('--color-about-backplate: #1A1A1A', false);
        $response->assertSee('--color-about-badge-dot: #FFFFFF', false);
        $response->assertDontSee('#EADBC8');
        $response->assertDontSee('#D5C0A8');
        $response->assertDontSee('#E06D24');
        $response->assertDontSee('#7A6B60');
        $response->assertDontSee('Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.');
        $response->assertSee('nucomu_story_1.png');
        $response->assertSee('nucomu_logo_icon.png');
        $response->assertSee('story-anim-caption', false);
        $response->assertSee('story-delay-6', false);
        $response->assertDontSee('sm:-rotate-[2.6deg]', false);
        $response->assertDontSee('story-anim-badge-coffee absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 lg:bottom-2.5 lg:right-2.5 z-20 w-[54px] h-[54px] sm:w-[60px] sm:h-[60px] lg:w-[66px] lg:h-[66px] bg-white hover:bg-neutral-200 active:scale-90 transition-all', false);
    }

    public function test_old_tentang_kami_route_no_longer_exists(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(404);
    }

    public function test_home_hero_sits_below_navbar_with_matching_initial_theme(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="hero-navbar-offset"', false);
        $response->assertSee('class="pt-16 sm:pt-20 transition-colors duration-[800ms]"', false);
        $response->assertSee('navbar--over-hero navbar--flush-hero', false);
        $response->assertSee("document.documentElement.style.setProperty('--hero-bg-color', \"#140E0A\")", false);
        $response->assertSee('data-navbar-theme="dark"', false);
    }

    public function test_home_page_renders_minimalist_menu_navigation_and_checkout_modal(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Explore Our Menu', 'Cara Pemesanan']);
        $response->assertSee('id="menu"', false);
        $response->assertSee('<section id="menu" class="pt-16 sm:pt-20 lg:pt-24 pb-12 sm:pb-16 lg:pb-20 bg-[#FFFFFF]', false);
        $response->assertSee('<section id="layanan" class="py-16 sm:py-20 lg:py-24 bg-[#FFFFFF]', false);
        $response->assertSee('<section id="contact" class="py-16 sm:py-20 lg:py-24 bg-[#FFFFFF]', false);
        $response->assertDontSee('#F5F2EC', false);
        $response->assertDontSee('#FAF8F4', false);
        $response->assertSee('Cormorant+Garamond', false);
        $response->assertSee('DM+Sans', false);
        $response->assertSee('Manrope:wght@400..700', false);
        $response->assertSee('id="cara-pemesanan-trigger"', false);
        $response->assertSee('id="cara-pemesanan-panel"', false);
        $response->assertSee('window.toggleCaraPemesananGuide', false);
        $response->assertSee('01 &middot; Pilih Menu &amp; Varian', false);
        $response->assertSee('Ketuk kartu sajian lalu tambah ke keranjang.');
        $response->assertSee('02 &middot; Checkout di Keranjang', false);
        $response->assertSee('03 &middot; Konfirmasi &amp; Bayar', false);
        $response->assertDontSee('id="cara-pemesanan-modal"', false);
        $response->assertDontSee('PANDUAN CHECKOUT');
        $response->assertSee('handleCircularCategoryClick', false);
        $response->assertSee('id="recommendations-viewport"', false);
        $response->assertSee('id="recommendations-carousel-controls"', false);
        $response->assertSee('id="rec-prev-btn"', false);
        $response->assertSee('id="rec-next-btn"', false);
        $response->assertSee('id="rec-page-dots"', false);
        $response->assertSee('id="rec-page-counter"', false);
        $response->assertSee('id="recommendations-columns-container"', false);
        $response->assertSee('window.goToMenuPage', false);
        $response->assertSee('window.updateMenuCarouselControls', false);
        $response->assertSee('numCols * 2', false);
        $menuNavigationSection = Str::between($response->getContent(), 'Explore Our Menu', 'id="recommendations-cards-pool"');
        $this->assertNotEmpty($menuNavigationSection);
        $this->assertStringNotContainsString('w-12 h-px', $menuNavigationSection);

        $response->assertDontSee('bg-[#F1EFEB]');
        $response->assertDontSee('OUR SELECTION');
        $response->assertDontSee('Menu Favorites');
        $response->assertDontSee('Our Favorites');
        $response->assertDontSee('Lihat Semua Menu');
        $response->assertDontSee('Racikan kopi, hidangan hangat, dan dessert favorit pengunjung Nucomu Cafe.');
        $response->assertDontSee('Pilih kategori untuk menelusuri sajian pilihan kami setiap hari.');
        $response->assertDontSee('Sajian unggulan yang paling sering dipilih untuk menemani waktu santai Anda.');
        $response->assertDontSee('nucomu-categories-title');
        $response->assertDontSee('KOLEKSI SAJIAN');
        $response->assertDontSee('HOW TO ORDER');
        $response->assertDontSee('Petunjuk Checkout');
        $response->assertDontSee('♔');
    }
}
