<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\NucomuController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OrderTahap8aTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_orders_index_with_three_mode_toggles_and_metrics(): void
    {
        $admin = $this->getAdminUser();

        Order::create([
            'code' => 'NCM-20261102-001',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'subtotal' => 50000,
            'total' => 50000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Transaksi');
        $response->assertSee('Makan di Cafe');
        $response->assertSee('Antar via Zendo');
        $response->assertSee('Hanya Chat ke Zendo');
        $response->assertSee('Template Pesan WhatsApp ke Zendo');
        $response->assertSee('NCM-20261102-001');
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Pesan via GrabFood');
    }

    public function test_admin_can_update_transaction_settings_with_mutual_exclusion_last_changed(): void
    {
        $admin = $this->getAdminUser();

        // Submit both delivery modes ON, but last changed was 'chat' -> tracking must be auto-disabled
        $response = $this->actingAs($admin)->post(route('admin.orders.updateSettings'), [
            'order_mode_dine_in' => '1',
            'order_mode_zendo_tracking' => '1',
            'order_mode_zendo_chat' => '1',
            'last_changed_mode' => 'chat',
            'order_dine_in_cash' => '1',
            'order_dine_in_qris' => '1',
            'zendo_whatsapp' => '085707300240',
            'zendo_chat_template' => "Halo Kurir Zendo!\nPesanan #{kode_pesanan} untuk {nama}.\nTotal: {total}",
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('order_mode_dine_in'));
        $this->assertEquals('0', Setting::get('order_mode_zendo_tracking'));
        $this->assertEquals('1', Setting::get('order_mode_zendo_chat'));
        $this->assertEquals('6285707300240', Setting::get('zendo_whatsapp'));
        $this->assertStringContainsString('Mode pelacakan kurir dimatikan otomatis', session('success'));
    }

    public function test_admin_mutual_exclusion_fallback_when_last_changed_is_missing(): void
    {
        $admin = $this->getAdminUser();

        // Submit both tracking and chat ON with no last_changed_mode -> fallback: tracking wins, chat disabled
        $response = $this->actingAs($admin)->post(route('admin.orders.updateSettings'), [
            'order_mode_dine_in' => '1',
            'order_mode_zendo_tracking' => '1',
            'order_mode_zendo_chat' => '1',
            'order_dine_in_cash' => '1',
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('order_mode_zendo_tracking'));
        $this->assertEquals('0', Setting::get('order_mode_zendo_chat'));
        $this->assertStringContainsString('Mode chat Zendo dimatikan otomatis', session('success'));
    }

    public function test_admin_can_update_dine_in_payment_options_and_qris(): void
    {
        $admin = $this->getAdminUser();

        $fakeFile = UploadedFile::fake()->image('qris.png', 400, 400);

        $response = $this->actingAs($admin)->post(route('admin.orders.updateSettings'), [
            'order_mode_dine_in' => '1',
            'order_dine_in_cash' => '1',
            'order_dine_in_qris' => '1',
            'qris_image' => $fakeFile,
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('order_dine_in_cash'));
        $this->assertEquals('1', Setting::get('order_dine_in_qris'));
        $this->assertNotEmpty(Setting::get('qris_image'));
        $this->assertStringContainsString('uploads/qris/', Setting::get('qris_image'));
    }

    public function test_admin_can_reset_chat_template_to_default(): void
    {
        $admin = $this->getAdminUser();

        Setting::set('zendo_chat_template', 'Custom modified template');

        $response = $this->actingAs($admin)->post(route('admin.orders.resetTemplate'));

        $response->assertSessionHas('success');
        $this->assertEquals(AdminOrderController::DEFAULT_ZENDO_TEMPLATE, Setting::get('zendo_chat_template'));
    }

    public function test_backward_compatibility_mapping_from_legacy_settings(): void
    {
        // Legacy keys in DB only (new keys absent)
        Setting::set('transaction_mode', 'both');
        Setting::set('hide_live_tracking', '1');

        $txSettings = (new NucomuController)->getTransactionSettings();

        $this->assertTrue($txSettings['order_mode_dine_in']);
        $this->assertFalse($txSettings['order_mode_zendo_tracking']);
        $this->assertTrue($txSettings['order_mode_zendo_chat']);
        $this->assertTrue($txSettings['is_any_mode_active']);
    }

    public function test_customer_can_order_via_zendo_and_get_whatsapp_url_with_compiled_template(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('zendo_whatsapp', '6285707300240');
        Setting::set('zendo_chat_template', "Halo Kurir Zendo!\nPesanan #{kode_pesanan} untuk {nama} ({telepon}).\nAlamat: {alamat}\nDaftar:\n{daftar_pesanan}\nTotal: {total}\nCatatan: {catatan}");

        $category = Category::create([
            'name' => 'Signature Coffee',
            'slug' => 'signature-coffee',
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu Gula Aren',
            'slug' => 'kopi-susu-gula-aren',
            'price' => 18000,
            'is_available' => true,
        ]);

        $payload = [
            'customer_name' => 'Siti Aisyah',
            'customer_phone' => '081299887766',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Pahlawan No. 12, Kedungwaru, Tulungagung',
            'distance_km' => 2.5,
            'notes' => 'Tolong sediakan sedotan ramah lingkungan',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => 18000,
                    'qty' => 2,
                    'line_total' => 36000,
                ],
            ],
            'total' => 36000,
        ];

        $response = $this->postJson(route('nucomu.order.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'process_method' => 'zendo',
        ]);

        $json = $response->json();
        $this->assertNotEmpty($json['whatsapp_url']);
        $this->assertStringContainsString('https://wa.me/6285707300240', $json['whatsapp_url']);

        // Decode URL to verify text content
        $decodedUrl = urldecode($json['whatsapp_url']);
        $this->assertStringContainsString('Siti Aisyah', $decodedUrl);
        $this->assertStringContainsString('081299887766', $decodedUrl);
        $this->assertStringContainsString('Jl. Pahlawan No. 12', $decodedUrl);
        $this->assertStringContainsString('Kopi Susu Gula Aren', $decodedUrl);
        $this->assertStringContainsString('Tolong sediakan sedotan ramah lingkungan', $decodedUrl);

        // Verify default template output contains expected estimate label
        Setting::forget('zendo_chat_template');
        $chatMsg = (new NucomuController)->formatZendoChatMessage(
            'NCM-TEST-CHAT',
            'Siti Aisyah',
            '081299887766',
            'Jl. Pahlawan No. 12',
            [['menu_name' => 'Kopi Susu Gula Aren', 'unit_price' => 18000, 'qty' => 2, 'line_total' => 36000]],
            36000,
            10000,
            46000,
            'Tolong sediakan sedotan ramah lingkungan',
            null,
            true // hideLiveTracking = true (Mode chat Zendo)
        );
        $this->assertStringContainsString('• Ongkir (perkiraan, final dikonfirmasi kurir): Rp 10.000', $chatMsg);
        $this->assertStringContainsString('• Total Pembayaran: Rp 46.000', $chatMsg);

        // Verify order in database
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Siti Aisyah',
            'delivery_address' => 'Jl. Pahlawan No. 12, Kedungwaru, Tulungagung',
            'status' => 'processing',
        ]);
    }

    public function test_zendo_order_is_blocked_when_zendo_mode_is_off(): void
    {
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('order_mode_zendo_chat', '0');
        Setting::set('order_mode_dine_in', '1');

        $payload = [
            'customer_name' => 'Budi',
            'customer_phone' => '081234567890',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Basuki Rahmat',
            'items' => [
                [
                    'menu_name' => 'Kopi Hitam',
                    'unit_price' => 10000,
                    'qty' => 1,
                    'line_total' => 10000,
                ],
            ],
            'total' => 10000,
        ];

        $response = $this->postJson(route('nucomu.order.store'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'Layanan pemesanan via Kurir Zendo sedang dinonaktifkan sementara oleh admin.',
        ]);
    }

    public function test_kasir_order_is_blocked_when_dine_in_mode_is_off(): void
    {
        Setting::set('order_mode_dine_in', '0');
        Setting::set('order_mode_zendo_chat', '1');

        $payload = [
            'customer_name' => 'Agus',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_name' => 'Espresso',
                    'unit_price' => 15000,
                    'qty' => 1,
                    'line_total' => 15000,
                ],
            ],
            'total' => 15000,
        ];

        $response = $this->postJson(route('nucomu.order.store'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'Layanan pemesanan makan di kafe sedang dinonaktifkan sementara oleh admin.',
        ]);
    }

    public function test_customer_order_is_blocked_when_all_modes_are_off(): void
    {
        Setting::set('order_mode_dine_in', '0');
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('order_mode_zendo_chat', '0');

        $payload = [
            'customer_name' => 'Agus',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_name' => 'Espresso',
                    'unit_price' => 15000,
                    'qty' => 1,
                    'line_total' => 15000,
                ],
            ],
            'total' => 15000,
        ];

        $response = $this->postJson(route('nucomu.order.store'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => 'Layanan pemesanan sedang dinonaktifkan sementara oleh admin.',
        ]);
    }

    public function test_menu_and_cart_do_not_display_grabfood_button(): void
    {
        Setting::set('grabfood_url', 'https://food.grab.com/id/id/restaurant/nucomu-cafe');

        $response = $this->get(route('menu.all'));

        $response->assertStatus(200);
        $response->assertDontSee('Pesan Menu via GrabFood');
    }

    public function test_all_modes_off_displays_neutral_catalog_notice(): void
    {
        Setting::set('order_mode_dine_in', '0');
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('order_mode_zendo_chat', '0');

        $response = $this->get(route('menu.all'));

        $response->assertStatus(200);
        $response->assertSee('Pemesanan online sedang tidak tersedia saat ini');
        $response->assertDontSee('+ Add to Cart');
    }

    public function test_admin_can_filter_orders_by_kind_and_status(): void
    {
        $admin = $this->getAdminUser();

        // 1. Cafe Dine In Order
        Order::create([
            'code' => 'NCM-TEST-CAFE',
            'customer_name' => 'Pelanggan Cafe',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'subtotal' => 20000,
            'total' => 20000,
            'status' => 'pending',
            'notes' => '[Nomor Meja: 03]',
        ]);

        // 2. Zendo Tracking Order
        Order::create([
            'code' => 'NCM-TEST-TRACK',
            'customer_name' => 'Pelanggan Tracking',
            'customer_phone' => '081234567891',
            'order_type' => 'take_away',
            'delivery_address' => 'Jl. Wilis Tulungagung',
            'delivery_fee' => 10000,
            'subtotal' => 30000,
            'total' => 40000,
            'status' => 'processing',
            'notes' => '[KURIR ZENDO] Kurir: Kurir Zendo 1 | Tahap_Zendo: delivering | Pin: -8.07,111.92',
        ]);

        // 3. Zendo Chat WA Order
        Order::create([
            'code' => 'NCM-TEST-CHAT',
            'customer_name' => 'Pelanggan Chat WA',
            'customer_phone' => '081234567892',
            'order_type' => 'take_away',
            'delivery_address' => 'Jl. Diponegoro Tulungagung',
            'delivery_fee' => 10000,
            'subtotal' => 25000,
            'total' => 35000,
            'status' => 'ready',
            'notes' => '[KURIR ZENDO] Alamat: Jl. Diponegoro Tulungagung',
        ]);

        // Test filter kind=dine_in
        $resDineIn = $this->actingAs($admin)->get(route('admin.orders.index', ['kind' => 'dine_in']));
        $resDineIn->assertStatus(200);
        $this->assertCount(1, $resDineIn->viewData('orders'));
        $this->assertEquals('NCM-TEST-CAFE', $resDineIn->viewData('orders')->first()->code);

        // Test filter kind=zendo_tracking
        $resTrack = $this->actingAs($admin)->get(route('admin.orders.index', ['kind' => 'zendo_tracking']));
        $resTrack->assertStatus(200);
        $this->assertCount(1, $resTrack->viewData('orders'));
        $this->assertEquals('NCM-TEST-TRACK', $resTrack->viewData('orders')->first()->code);

        // Test filter kind=zendo_chat
        $resChat = $this->actingAs($admin)->get(route('admin.orders.index', ['kind' => 'zendo_chat']));
        $resChat->assertStatus(200);
        $this->assertCount(1, $resChat->viewData('orders'));
        $this->assertEquals('NCM-TEST-CHAT', $resChat->viewData('orders')->first()->code);

        // Test filter status=ready
        $resReady = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'ready']));
        $resReady->assertStatus(200);
        $this->assertCount(1, $resReady->viewData('orders'));
        $this->assertEquals('NCM-TEST-CHAT', $resReady->viewData('orders')->first()->code);
    }

    public function test_admin_can_update_order_status_from_table_dropdown(): void
    {
        $admin = $this->getAdminUser();

        $order = Order::create([
            'code' => 'NCM-TEST-STATUS',
            'customer_name' => 'Budi',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'subtotal' => 15000,
            'total' => 15000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.updateStatus', $order->id), [
            'status' => 'ready',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('ready', $order->fresh()->status);
    }

    public function test_server_strictly_recalculates_prices_and_ignores_client_tampering(): void
    {
        Setting::set('order_mode_dine_in', '1');
        Setting::set('order_mode_zendo_tracking', '1');
        Setting::set('order_mode_zendo_chat', '0');
        Setting::set('zendo_tier_0_3', '10000');

        $category = Category::create([
            'name' => 'Signature Blend',
            'slug' => 'signature-blend',
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Caramel Macchiato',
            'slug' => 'caramel-macchiato',
            'price' => 25000,
            'is_available' => true,
        ]);

        // Client tampers with unit_price: 500, line_total: 1000, total: 1000 (instead of real 25.000 x 2 = 50.000)
        $tamperedPayload = [
            'customer_name' => 'Hacker Tamper',
            'customer_phone' => '081299998888',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'menu_name' => 'Caramel Macchiato',
                    'unit_price' => 500, // Tampered!
                    'qty' => 2,
                    'line_total' => 1000, // Tampered!
                ],
            ],
            'total' => 1000, // Tampered!
        ];

        $response = $this->postJson(route('nucomu.order.store'), $tamperedPayload);

        $response->assertStatus(200);
        $json = $response->json();

        // Must be recalculated to real 50.000
        $this->assertEquals(50000, $json['subtotal']);
        $this->assertEquals(50000, $json['grand_total']);

        // Check database order record
        $order = Order::where('customer_name', 'Hacker Tamper')->first();
        $this->assertNotNull($order);
        $this->assertEquals(50000, $order->subtotal);
        $this->assertEquals(50000, $order->total);

        // Check database order item record
        $orderItem = $order->items()->first();
        $this->assertNotNull($orderItem);
        $this->assertEquals(25000, $orderItem->unit_price);
        $this->assertEquals(50000, $orderItem->line_total);

        // Test with Zendo delivery mode + fee tampering
        $tamperedZendoPayload = [
            'customer_name' => 'Hacker Delivery',
            'customer_phone' => '081299997777',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Iswahyudi No. 5, Tulungagung',
            'distance_km' => 2.0,
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'menu_name' => 'Caramel Macchiato',
                    'unit_price' => 10, // Tampered!
                    'qty' => 3,
                    'line_total' => 30, // Tampered!
                ],
            ],
            'total' => 30, // Tampered!
        ];

        $zendoRes = $this->postJson(route('nucomu.order.store'), $tamperedZendoPayload);
        $zendoRes->assertStatus(200);
        $zendoJson = $zendoRes->json();

        // 3 x 25.000 = 75.000 + 10.000 (ongkir 2km tier_0_3) = 85.000
        $this->assertEquals(75000, $zendoJson['subtotal']);
        $this->assertEquals(10000, $zendoJson['delivery_fee']);
        $this->assertEquals(85000, $zendoJson['grand_total']);

        $zendoOrder = Order::where('customer_name', 'Hacker Delivery')->first();
        $this->assertEquals(75000, $zendoOrder->subtotal);
        $this->assertEquals(10000, $zendoOrder->delivery_fee);
        $this->assertEquals(85000, $zendoOrder->total);

        // WhatsApp message must contain server values
        $decodedWa = urldecode($zendoJson['whatsapp_url']);
        $this->assertStringContainsString('Rp 75.000', $decodedWa);
        $this->assertStringContainsString('Rp 85.000', $decodedWa);
        $this->assertStringNotContainsString('Rp 30', $decodedWa);
    }

    public function test_delivery_fee_tampering_is_ignored_and_menu_qty_are_strictly_validated(): void
    {
        Setting::set('order_mode_dine_in', '1');
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('zendo_tier_0_3', '10000');

        $category = Category::create([
            'name' => 'Pastry Test',
            'slug' => 'pastry-test',
            'is_active' => true,
        ]);

        $availableMenu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Croissant Almond',
            'slug' => 'croissant-almond',
            'price' => 30000,
            'is_available' => true,
        ]);

        $unavailableMenu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Cromboloni Pistachio',
            'slug' => 'cromboloni-pistachio',
            'price' => 35000,
            'is_available' => false,
        ]);

        // 1. Client attempts to set delivery_fee: 0 and distance_km: 0
        $tamperedDelivery = [
            'customer_name' => 'Tamper Ongkir',
            'customer_phone' => '081234500000',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Pahlawan No. 99, Tulungagung',
            'distance_km' => 0,
            'delivery_fee' => 0, // Injected!
            'items' => [
                [
                    'menu_id' => $availableMenu->id,
                    'menu_name' => $availableMenu->name,
                    'unit_price' => 30000,
                    'qty' => 1,
                    'line_total' => 30000,
                ],
            ],
            'total' => 30000, // Client tries to omit delivery fee
        ];

        $resFee = $this->postJson(route('nucomu.order.store'), $tamperedDelivery);
        $resFee->assertStatus(200);
        $jsonFee = $resFee->json();

        // Server strictly applies tier_0_3 = 10.000, so grand total must be 30.000 + 10.000 = 40.000
        $this->assertEquals(10000, $jsonFee['delivery_fee']);
        $this->assertEquals(40000, $jsonFee['grand_total']);

        // 2. Client passes invalid qty = 0
        $invalidQty0 = [
            'customer_name' => 'Invalid Qty 0',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => $availableMenu->id,
                    'menu_name' => $availableMenu->name,
                    'qty' => 0,
                ],
            ],
        ];
        $resQty0 = $this->postJson(route('nucomu.order.store'), $invalidQty0);
        $resQty0->assertStatus(422);
        $this->assertStringContainsString('bilangan bulat minimal 1', $resQty0->json('message'));

        // 3. Client passes invalid qty = 1.5 (float)
        $invalidQtyFloat = [
            'customer_name' => 'Invalid Qty Float',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => $availableMenu->id,
                    'menu_name' => $availableMenu->name,
                    'qty' => 1.5,
                ],
            ],
        ];
        $resQtyFloat = $this->postJson(route('nucomu.order.store'), $invalidQtyFloat);
        $resQtyFloat->assertStatus(422);
        $this->assertStringContainsString('bilangan bulat minimal 1', $resQtyFloat->json('message'));

        // 4. Client passes non-existent menu_id
        $nonExistentMenu = [
            'customer_name' => 'Ghost Menu',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => 999999,
                    'menu_name' => 'Ghost Item',
                    'qty' => 1,
                ],
            ],
        ];
        $resGhost = $this->postJson(route('nucomu.order.store'), $nonExistentMenu);
        $resGhost->assertStatus(422);
        $this->assertStringContainsString('tidak ditemukan', $resGhost->json('message'));

        // 5. Client passes menu with is_available = false
        $unavailableReq = [
            'customer_name' => 'Unavailable Menu',
            'process_method' => 'kasir',
            'items' => [
                [
                    'menu_id' => $unavailableMenu->id,
                    'menu_name' => $unavailableMenu->name,
                    'qty' => 1,
                ],
            ],
        ];
        $resUnavail = $this->postJson(route('nucomu.order.store'), $unavailableReq);
        $resUnavail->assertStatus(422);
        $this->assertStringContainsString('sedang tidak tersedia', $resUnavail->json('message'));
    }
}
