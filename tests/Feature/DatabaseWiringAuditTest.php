<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\MenuOption;
use App\Models\OperatingHour;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseWiringAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_database_about_media_operating_hours_branding_and_facilities(): void
    {
        Setting::set('cafe_name', 'Nucomu Heritage');
        Setting::set('cafe_address', 'Jl. Uji Database No. 88, Tulungagung');
        Setting::set('cafe_landmark', 'Sebelah Taman Kota Uji');
        Setting::set('cafe_maps_url', 'https://maps.google.com/?q=Nucomu+Uji');
        Setting::set('cafe_opening_hours', 'Setiap Hari: 09:00 – 23:00 WIB');
        Setting::set('cafe_logo', 'images/nucomu/nucomu_logo_brand.png');
        Setting::set('cafe_favicon', 'images/nucomu/nucomu_logo_icon.png');
        Setting::set('about_media_type', 'image');
        Setting::set('about_media_path', 'images/nucomu/nucomu_story_2.png');
        Setting::set('services_reservasi_desc', 'Deskripsi reservasi langsung dari database.');
        Setting::set('services_delivery_desc', 'Deskripsi delivery langsung dari database.');
        Setting::set('services_online_desc', 'Deskripsi grabfood langsung dari database.');

        $hours = [
            ['day_of_week' => 1, 'day_name' => 'Senin',  'open_time' => null,    'close_time' => null,    'is_closed' => true],
            ['day_of_week' => 2, 'day_name' => 'Selasa', 'open_time' => '09:00', 'close_time' => '20:00', 'is_closed' => false],
            ['day_of_week' => 3, 'day_name' => 'Rabu',   'open_time' => '09:00', 'close_time' => '20:00', 'is_closed' => false],
            ['day_of_week' => 4, 'day_name' => 'Kamis',  'open_time' => '09:00', 'close_time' => '20:00', 'is_closed' => false],
            ['day_of_week' => 5, 'day_name' => 'Jumat',  'open_time' => '09:00', 'close_time' => '20:00', 'is_closed' => false],
            ['day_of_week' => 6, 'day_name' => 'Sabtu',  'open_time' => '10:00', 'close_time' => '22:00', 'is_closed' => false],
            ['day_of_week' => 0, 'day_name' => 'Minggu', 'open_time' => '10:00', 'close_time' => '22:00', 'is_closed' => false],
        ];
        foreach ($hours as $h) {
            OperatingHour::updateOrCreate(['day_of_week' => $h['day_of_week']], $h);
        }

        Facility::updateOrCreate(
            ['slug' => 'indoor'],
            [
                'tab_name' => 'INDOOR',
                'title' => 'Indoor Database Custom',
                'label' => 'Indoor Database Custom',
                'badge' => '01 / INDOOR',
                'subtitle' => 'Ruang Kerja Sejuk',
                'description' => 'Fasilitas indoor dari database.',
                'features' => ['AC Dingin'],
                'photos' => [
                    ['image' => 'images/nucomu/nucomu_story_1.png', 'caption' => 'Caption Indoor DB', 'alt' => 'Indoor DB Alt'],
                ],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('id="about-room-tour-image"', false);
        $response->assertSee('images/nucomu/nucomu_story_2.png', false);
        $response->assertSee('Nucomu Heritage');
        $response->assertSee('Jl. Uji Database No. 88, Tulungagung');
        $response->assertSee('Sebelah Taman Kota Uji');
        $response->assertSee('https://maps.google.com/?q=Nucomu+Uji', false);
        $response->assertSee('Setiap Hari: 09:00 – 23:00 WIB');
        $response->assertSee('<link rel="icon"', false);
        $response->assertSee('Libur / Tutup');
        $response->assertSee('09:00 – 20:00 WIB');
        $response->assertSee('10:00 – 22:00 WIB');
        $response->assertSee('Deskripsi reservasi langsung dari database.');
        $response->assertSee('Deskripsi delivery langsung dari database.');
        $response->assertSee('Deskripsi grabfood langsung dari database.');
        $response->assertSee('Caption Indoor DB');
    }

    public function test_all_menu_page_filters_accurately_by_database_category_slugs_and_aliases(): void
    {
        $kopiCat = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 1, 'is_active' => true]);
        $makananCat = Category::create(['name' => 'Makanan', 'slug' => 'makanan', 'sort_order' => 2, 'is_active' => true]);
        $customCat = Category::create(['name' => 'Signature Baru', 'slug' => 'signature-baru', 'sort_order' => 3, 'is_active' => true]);

        Menu::create([
            'category_id' => $kopiCat->id,
            'name' => 'Espresso Uji DB',
            'slug' => 'espresso-uji-db',
            'price' => 21000,
            'is_available' => true,
            'sort_order' => 1,
        ]);
        Menu::create([
            'category_id' => $makananCat->id,
            'name' => 'Rice Bowl Uji DB',
            'slug' => 'rice-bowl-uji-db',
            'price' => 34000,
            'is_available' => true,
            'sort_order' => 2,
        ]);
        Menu::create([
            'category_id' => $customCat->id,
            'name' => 'Mocktail Khusus DB',
            'slug' => 'mocktail-khusus-db',
            'price' => 29000,
            'is_available' => true,
            'sort_order' => 3,
        ]);

        // Exact DB category slug (?category=kopi)
        $kopiRes = $this->get(route('menu.all', ['category' => 'kopi']));
        $kopiRes->assertOk();
        $kopiRes->assertSee('Espresso Uji DB');
        $kopiRes->assertDontSee('Rice Bowl Uji DB');
        $kopiRes->assertDontSee('Mocktail Khusus DB');

        // Legacy alias (?category=coffee)
        $aliasRes = $this->get(route('menu.all', ['category' => 'coffee']));
        $aliasRes->assertOk();
        $aliasRes->assertSee('Espresso Uji DB');
        $aliasRes->assertDontSee('Rice Bowl Uji DB');

        // Custom admin category (?category=signature-baru)
        $customRes = $this->get(route('menu.all', ['category' => 'signature-baru']));
        $customRes->assertOk();
        $customRes->assertSee('Mocktail Khusus DB');
        $customRes->assertDontSee('Espresso Uji DB');

        // All menu does not inject deleted/config items when DB menus exist
        $allRes = $this->get(route('menu.all'));
        $allRes->assertOk();
        $allRes->assertSee('Espresso Uji DB');
        $allRes->assertSee('Rice Bowl Uji DB');
        $allRes->assertSee('Mocktail Khusus DB');
        $allRes->assertDontSee('Nucomu Signature Latte');
    }

    public function test_public_checkout_track_order_contact_events_menu_detail_and_nucomu_pages_render_cleanly(): void
    {
        Setting::set('cafe_opening_hours', 'Senin – Minggu: 10:00 – 22:00 WIB');
        $cat = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $cat->id,
            'name' => 'Latte Uji Detail',
            'slug' => 'latte-uji-detail',
            'price' => 26000,
            'image' => 'images/nucomu/latte_grabfood.jpg',
            'is_available' => true,
        ]);
        Table::create(['number' => 'T1', 'capacity' => 4, 'is_active' => true]);

        // 1. GET /checkout with cart session works without missing view error
        $checkoutRes = $this->withSession([
            'cart' => [
                'item_1' => [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => 26000,
                    'qty' => 1,
                    'options' => [],
                    'line_total' => 26000,
                ],
            ],
        ])->get(route('checkout'));
        $checkoutRes->assertOk();
        $checkoutRes->assertSee('Latte Uji Detail');

        // 2. GET /lacak-pesanan renders real code (not literal Blade braces) and supports ready status
        $order = Order::create([
            'code' => 'NCM-TEST-READY-001',
            'customer_name' => 'Budi Pelanggan',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'subtotal' => 26000,
            'total' => 26000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'ready',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'unit_price' => 26000,
            'qty' => 1,
            'line_total' => 26000,
        ]);

        $trackRes = $this->get(route('order.track', ['code' => $order->code]));
        $trackRes->assertOk();
        $trackRes->assertSee('NCM-TEST-READY-001');
        $trackRes->assertDontSee('{ $order->code }', false);
        $trackRes->assertSee('Siap Diambil');

        // 3. GET /kontak renders cleanly
        $this->get(route('contact'))->assertOk();

        // 4. GET /events handles nullable event date without 1970 fallback
        Event::create([
            'title' => 'Acoustic Night Fleksibel',
            'description' => 'Acara musik santai tanpa tanggal tetap.',
            'date' => null,
            'is_active' => true,
        ]);
        $eventsRes = $this->get(route('events'));
        $eventsRes->assertOk();
        $eventsRes->assertSee('Acoustic Night Fleksibel');
        $eventsRes->assertDontSee('1970');

        // 5. GET /menu/{slug} renders DB image_url cleanly
        $detailRes = $this->get(route('menu.show', $menu->slug));
        $detailRes->assertOk();
        $detailRes->assertSee('Latte Uji Detail');
        $detailRes->assertSee('images/nucomu/latte_grabfood.jpg', false);

        // 6. GET /nucomu defines DEFAULT_AVATAR and renders DB opening hours
        $nucomuRes = $this->get(route('nucomu.index'));
        $nucomuRes->assertOk();
        $nucomuRes->assertSee('const DEFAULT_AVATAR =', false);
        $nucomuRes->assertSee('Senin – Minggu: 10:00 – 22:00 WIB');
    }

    public function test_admin_receipt_dashboard_ready_status_and_zendo_notifications_use_database(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Setting::set('cafe_name', 'Nucomu Cafe Official');
        Setting::set('cafe_address', 'Jl. Mayor Sujadi No. 123, Plosokandang');
        Setting::set('cafe_whatsapp', '6285934960910');
        Setting::set('cafe_instagram', 'https://www.instagram.com/nucomu.tulungagung');

        $readyOrder = Order::create([
            'code' => 'NCM-READY-901',
            'customer_name' => 'Sari Ready',
            'customer_phone' => '081200001111',
            'order_type' => 'dine_in',
            'subtotal' => 30000,
            'total' => 30000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'status' => 'ready',
        ]);

        $zendoOrder = Order::create([
            'code' => 'NCM-ZENDO-902',
            'customer_name' => 'Raka Zendo',
            'customer_phone' => '081200002222',
            'order_type' => 'take_away',
            'delivery_address' => 'Jl. Merdeka Barat No. 5',
            'delivery_fee' => 10000,
            'notes' => '[KURIR ZENDO] Alamat: Jl. Merdeka Barat No. 5 | Ongkir: Rp 10.000',
            'subtotal' => 25000,
            'total' => 35000,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        // Receipt uses DB settings instead of old hardcoded address
        $receiptRes = $this->actingAs($admin)->get(route('admin.orders.receipt', $readyOrder));
        $receiptRes->assertOk();
        $receiptRes->assertSee('NUCOMU CAFE OFFICIAL');
        $receiptRes->assertSee('Jl. Mayor Sujadi No. 123, Plosokandang');
        $receiptRes->assertSee('+62 859-3496-0910');
        $receiptRes->assertSee('@nucomu.tulungagung');
        $receiptRes->assertDontSee('Jl. Panglima Sudirman No. 45, Kebonsari');

        // Dashboard shows "Siap" badge for ready status instead of falling into "Batal"
        $dashRes = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashRes->assertOk();
        $dashRes->assertSee('NCM-READY-901');
        $dashRes->assertSee('Siap');

        // Public QR slip detail (as guest) shows "Siap Diambil" for ready status
        auth()->logout();
        $qrRes = $this->get(route('admin.orders.qr', $readyOrder->code));
        $qrRes->assertOk();
        $qrRes->assertSee('Siap Diambil');

        // Admin notifications accurately identify Zendo order
        $notifRes = $this->actingAs($admin)->get(route('admin.notifications.index'));
        $notifRes->assertOk();
        $notifRes->assertSee($zendoOrder->code);
        $notifRes->assertSee('Antar Kurir Zendo');
    }

    public function test_store_order_resolves_slug_and_option_extra_price_from_database_and_rejects_unknown_id(): void
    {
        $cat = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $cat->id,
            'name' => 'Kopi Aren Uji',
            'slug' => 'kopi-aren-uji',
            'price' => 25000,
            'stock' => 10,
            'is_available' => true,
        ]);
        MenuOption::create([
            'menu_id' => $menu->id,
            'name' => 'Extra Shot',
            'type' => 'addon',
            'price' => 5000,
            'is_sample' => false,
        ]);

        // Valid order using slug + addon option calculates server-side price (25.000 + 5.000 = 30.000)
        $okRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Dina Pelanggan',
            'process_method' => 'kasir',
            'items' => [
                [
                    'id' => 'kopi-aren-uji',
                    'name' => 'Kopi Aren Uji',
                    'price' => 1000, // Client tampered price must be ignored
                    'qty' => 2,
                    'options' => ['Extra Shot'],
                ],
            ],
        ]);
        $okRes->assertOk();
        $okRes->assertJsonPath('subtotal', 60000);
        $okRes->assertJsonPath('grand_total', 60000);

        $this->assertDatabaseHas('order_items', [
            'menu_id' => $menu->id,
            'menu_name' => 'Kopi Aren Uji',
            'unit_price' => 30000,
            'qty' => 2,
            'line_total' => 60000,
        ]);

        // Explicit invalid numeric menu_id must be rejected even if name matches an existing menu
        $badRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Penipu ID',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => 888888,
                    'menu_name' => 'Kopi Aren Uji',
                    'qty' => 1,
                ],
            ],
        ]);
        $badRes->assertStatus(422);
        $this->assertStringContainsString('tidak ditemukan', (string) $badRes->json('message'));
    }

    public function test_database_seeder_runs_idempotently_and_populates_valid_connected_data(): void
    {
        $this->seed();
        $this->seed();

        $this->assertSame(5, Category::count());
        $this->assertGreaterThanOrEqual(20, Menu::count());
        $this->assertGreaterThan(0, MenuOption::count());
        $this->assertSame(9, Table::count());
        $this->assertSame(7, OperatingHour::count());
        $this->assertSame(3, Facility::whereNotNull('slug')->count());
        $this->assertGreaterThan(0, GalleryItem::count());
        $this->assertDatabaseHas('users', ['email' => 'admin@nucomu.com', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'kasir@nucomu.com', 'role' => 'kasir']);

        // Verify every seeded gallery item points to an actual file on disk
        foreach (GalleryItem::all() as $item) {
            $this->assertFileExists(public_path($item->image));
        }

        // Verify public pages render cleanly on fully seeded database
        $this->get(route('home'))->assertOk();
        $this->get(route('menu.all'))->assertOk();
        $this->get(route('menu.all', ['category' => 'kopi']))->assertOk()->assertSee('Nucomu Coffee');
    }
}
