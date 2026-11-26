<?php

namespace Tests\Feature;

use App\Http\Controllers\KasirController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionChannelAndBarcodeTest extends TestCase
{
    use RefreshDatabase;

    private function createKasirCookie(): string
    {
        KasirController::ensureDefaultKasirUser();
        $loginResponse = $this->post(route('kasir.login.post'), [
            'password' => 'password123',
        ]);
        $cookie = $loginResponse->getCookie(KasirController::KASIR_COOKIE, false)->getValue();
        auth()->logout();

        return $cookie;
    }

    private function createSampleMenu(): Menu
    {
        $category = Category::create([
            'name' => 'Kopi Signature',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        return Menu::create([
            'category_id' => $category->id,
            'name' => 'Nucomu Signature Latte',
            'slug' => 'nucomu-signature-latte',
            'price' => 25000,
            'stock' => 20,
            'is_available' => true,
        ]);
    }

    public function test_customer_direct_order_supports_linear_barcode_and_slip_mode_switcher(): void
    {
        Setting::set('order_mode_dine_in', '1');
        $menu = $this->createSampleMenu();

        $response = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Raka Pratama',
            'customer_phone' => '081234567890',
            'process_method' => 'kasir',
            'barcode_type' => 'barcode',
            'dine_type' => 'dine_in',
            'table_no' => '05',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => 25000,
                    'qty' => 2,
                    'line_total' => 50000,
                ],
            ],
            'total' => 50000,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'process_method' => 'kasir',
            'order_channel' => Order::CHANNEL_DIRECT,
            'barcode_type' => Order::BARCODE_LINEAR,
            'uses_barcode' => true,
        ]);

        $orderCode = $response->json('order_code');
        $slipUrl = $response->json('slip_url');
        $this->assertNotEmpty($slipUrl);
        $this->assertStringContainsString('barcode_mode=barcode', $slipUrl);

        $order = Order::where('code', $orderCode)->firstOrFail();
        $this->assertSame(Order::CHANNEL_DIRECT, $order->channel);
        $this->assertSame(Order::BARCODE_LINEAR, $order->effective_barcode_type);
        $this->assertTrue($order->uses_barcode);

        $slipResponse = $this->get($slipUrl);
        $slipResponse->assertOk();
        $slipResponse->assertSee('Barcode Garis');
        $slipResponse->assertSee('QR Code');
        $slipResponse->assertSee('nucomu-barcode.js');
        $slipResponse->assertSee($orderCode);
    }

    public function test_zendo_and_grab_orders_have_no_barcode_and_cannot_open_barcode_slip(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('order_mode_zendo_tracking', '0');
        $menu = $this->createSampleMenu();

        $zendoRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Nadia Zendo',
            'customer_phone' => '081234567899',
            'process_method' => 'zendo',
            'barcode_type' => 'barcode', // Must be ignored for Zendo
            'delivery_address' => 'Jl. Diponegoro No. 10, Tulungagung',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'unit_price' => 25000,
                    'qty' => 1,
                    'line_total' => 25000,
                ],
            ],
            'total' => 35000,
        ]);

        $zendoRes->assertOk();
        $zendoRes->assertJson([
            'success' => true,
            'order_channel' => Order::CHANNEL_ZENDO,
            'barcode_type' => null,
            'uses_barcode' => false,
            'slip_url' => null,
        ]);

        $zendoOrder = Order::where('code', $zendoRes->json('order_code'))->firstOrFail();
        $this->assertFalse($zendoOrder->uses_barcode);
        $this->assertNull($zendoOrder->effective_barcode_type);

        // Visiting slip route for Zendo or Grab redirects away (no barcode slip)
        $this->get(route('nucomu.order.slip', ['code' => $zendoOrder->code]))
            ->assertRedirect(route('nucomu.order'));

        $grabOrder = Order::factory()->grab()->create([
            'code' => 'NCM-20261126-777',
            'customer_name' => 'Driver Grab',
        ]);

        $this->assertFalse($grabOrder->uses_barcode);
        $this->assertNull($grabOrder->effective_barcode_type);
        $this->get(route('nucomu.order.slip', ['code' => $grabOrder->code]))
            ->assertRedirect(route('nucomu.order'));
    }

    public function test_kasir_pos_supports_direct_zendo_and_grab_channels_with_logos_and_receipt_rules(): void
    {
        $kasirCookie = $this->createKasirCookie();
        $menu = $this->createSampleMenu();

        // 1. Kasir workspace displays channel selector, logos, photo fallback, and hardware scanner UI
        $kasirPage = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.index'));
        $kasirPage->assertOk();
        $kasirPage->assertSee('Sumber transaksi');
        $kasirPage->assertSee('images/logos/zendo-courier.png');
        $kasirPage->assertSee('images/logos/grabfood.png');
        $kasirPage->assertSee('nucomu-barcode.js');
        $kasirPage->assertSee('Scanner USB/BT Aktif');
        $kasirPage->assertSee('Scan via Foto Barcode / QR');

        // 2. Create Direct (Barcode Garis) order in Kasir
        $directRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.store'), [
                'customer_name' => 'Tamu Cafe',
                'order_type' => 'dine_in',
                'order_channel' => Order::CHANNEL_DIRECT,
                'barcode_type' => Order::BARCODE_LINEAR,
                'table_no' => '02',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'items' => [
                    [
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'unit_price' => 25000,
                        'qty' => 1,
                    ],
                ],
            ]);

        $directRes->assertOk();
        $directRes->assertJsonPath('order.order_channel', Order::CHANNEL_DIRECT);
        $directRes->assertJsonPath('order.uses_barcode', true);
        $directRes->assertJsonPath('order.barcode_type', Order::BARCODE_LINEAR);
        $this->assertNotNull($directRes->json('qr_url'));
        $this->assertNotNull($directRes->json('slip_url'));

        // Direct receipt displays switchable barcode & QR
        $directReceipt = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $directRes->json('order.id')));
        $directReceipt->assertOk();
        $directReceipt->assertSee('receiptBarcodeTarget', false);
        $directReceipt->assertSee('Barcode Garis');

        // 3. Create Zendo manual order in Kasir -> NO barcode, returns Zendo logo
        $zendoRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.store'), [
                'customer_name' => 'Kurir Zendo Anton',
                'order_type' => 'dine_in', // Forced to take_away for partner orders
                'order_channel' => Order::CHANNEL_ZENDO,
                'barcode_type' => Order::BARCODE_LINEAR, // Must be ignored
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'items' => [
                    [
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'unit_price' => 25000,
                        'qty' => 2,
                    ],
                ],
            ]);

        $zendoRes->assertOk();
        $zendoRes->assertJsonPath('order.order_channel', Order::CHANNEL_ZENDO);
        $zendoRes->assertJsonPath('order.order_type', 'take_away');
        $zendoRes->assertJsonPath('order.uses_barcode', false);
        $zendoRes->assertJsonPath('order.barcode_type', null);
        $this->assertNull($zendoRes->json('qr_url'));
        $this->assertNull($zendoRes->json('slip_url'));
        $this->assertStringContainsString('images/logos/zendo-courier.png', (string) $zendoRes->json('order.channel_logo'));

        $zendoReceipt = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $zendoRes->json('order.id')));
        $zendoReceipt->assertOk();
        $zendoReceipt->assertSee('TRANSAKSI ZENDO');
        $zendoReceipt->assertSee('Tanpa Barcode');
        $zendoReceipt->assertSee('images/logos/zendo-courier.png');
        $zendoReceipt->assertDontSee('receiptBarcodeTarget', false);

        // 4. Create Grab manual order in Kasir -> NO barcode, returns GrabFood logo
        $grabRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.store'), [
                'customer_name' => 'GF-8821 Andi',
                'order_type' => 'take_away',
                'order_channel' => Order::CHANNEL_GRAB,
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'items' => [
                    [
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'unit_price' => 25000,
                        'qty' => 3,
                    ],
                ],
            ]);

        $grabRes->assertOk();
        $grabRes->assertJsonPath('order.order_channel', Order::CHANNEL_GRAB);
        $grabRes->assertJsonPath('order.uses_barcode', false);
        $grabRes->assertJsonPath('order.barcode_type', null);
        $this->assertNull($grabRes->json('qr_url'));
        $this->assertNull($grabRes->json('slip_url'));
        $this->assertStringContainsString('images/logos/grabfood.png', (string) $grabRes->json('order.channel_logo'));

        $grabReceipt = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $grabRes->json('order.id')));
        $grabReceipt->assertOk();
        $grabReceipt->assertSee('TRANSAKSI GRABFOOD');
        $grabReceipt->assertSee('Tanpa Barcode');
        $grabReceipt->assertSee('images/logos/grabfood.png');
        $grabReceipt->assertDontSee('receiptBarcodeTarget', false);

        // 5. Sync endpoint includes channel metadata for live separated orders
        $syncRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->getJson(route('kasir.sync'));
        $syncRes->assertOk();
        $syncedChannels = collect($syncRes->json('recent_orders'))->pluck('order_channel')->all();
        $this->assertContains(Order::CHANNEL_DIRECT, $syncedChannels);
        $this->assertContains(Order::CHANNEL_ZENDO, $syncedChannels);
        $this->assertContains(Order::CHANNEL_GRAB, $syncedChannels);
    }

    public function test_admin_separates_transaction_history_by_direct_zendo_and_grab_and_exports_recap(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $directOrder = Order::factory()->linearBarcode()->create([
            'code' => 'NCM-20261126-101',
            'customer_name' => 'Pelanggan Langsung',
            'subtotal' => 40000,
            'total' => 40000,
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $zendoOrder = Order::factory()->zendo()->create([
            'code' => 'NCM-20261126-102',
            'customer_name' => 'Pelanggan Zendo',
            'subtotal' => 55000,
            'total' => 55000,
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $grabOrder = Order::factory()->grab()->create([
            'code' => 'NCM-20261126-103',
            'customer_name' => 'Pelanggan GrabFood',
            'subtotal' => 70000,
            'total' => 70000,
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        // Index page shows separated summary cards & wrapper id for teammate's hide feature
        $indexRes = $this->actingAs($admin)->get(route('admin.orders.index'));
        $indexRes->assertOk();
        $indexRes->assertSee('id="transaction-history-section"', false);
        $indexRes->assertSee('Riwayat Transaksi Terpisah per Sumber');
        $indexRes->assertSee('images/logos/zendo-courier.png');
        $indexRes->assertSee('images/logos/grabfood.png');
        $this->assertSame(1, $indexRes->viewData('totalChannelDirect'));
        $this->assertSame(1, $indexRes->viewData('totalChannelZendo'));
        $this->assertSame(1, $indexRes->viewData('totalChannelGrab'));

        // Filter channel=direct
        $resDirect = $this->actingAs($admin)->get(route('admin.orders.index', ['channel' => 'direct']));
        $resDirect->assertOk();
        $this->assertCount(1, $resDirect->viewData('orders'));
        $this->assertSame($directOrder->code, $resDirect->viewData('orders')->first()->code);

        // Filter channel=zendo
        $resZendo = $this->actingAs($admin)->get(route('admin.orders.index', ['channel' => 'zendo']));
        $resZendo->assertOk();
        $this->assertCount(1, $resZendo->viewData('orders'));
        $this->assertSame($zendoOrder->code, $resZendo->viewData('orders')->first()->code);

        // Filter channel=grab
        $resGrab = $this->actingAs($admin)->get(route('admin.orders.index', ['channel' => 'grab']));
        $resGrab->assertOk();
        $this->assertCount(1, $resGrab->viewData('orders'));
        $this->assertSame($grabOrder->code, $resGrab->viewData('orders')->first()->code);

        // Export PDF filtered by channel=grab contains ONLY the Grab order
        $pdfRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'monthly',
            'month' => now()->format('Y-m'),
            'channel' => 'grab',
            'format' => 'pdf',
        ]));
        $pdfRes->assertOk();
        $pdfRes->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString($grabOrder->code, $pdfRes->getContent());
        $this->assertStringNotContainsString($directOrder->code, $pdfRes->getContent());
        $this->assertStringNotContainsString($zendoOrder->code, $pdfRes->getContent());
    }

    public function test_admin_scan_page_extracts_order_code_from_both_linear_barcode_and_qr_url(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->linearBarcode()->create([
            'code' => 'NCM-20261126-505',
            'customer_name' => 'Pemindai Barcode',
        ]);

        // 1. Plain 1D barcode string (typed by USB/Bluetooth scanner)
        $resBarcode = $this->actingAs($admin)->get(route('admin.orders.scan.search', [
            'code' => 'NCM-20261126-505',
        ]));
        $resBarcode->assertOk();
        $resBarcode->assertSee('NCM-20261126-505');
        $resBarcode->assertSee('Pemindai Barcode');
        $resBarcode->assertSee('nucomu-barcode.js');
        $resBarcode->assertSee('adminHttpCameraNotice', false);

        // 2. Full QR URL scanned over LAN IP
        $resQrUrl = $this->actingAs($admin)->get(route('admin.orders.scan.search', [
            'code' => 'http://192.168.137.221:8000/qr/pesanan/NCM-20261126-505',
        ]));
        $resQrUrl->assertOk();
        $resQrUrl->assertSee('NCM-20261126-505');
        $resQrUrl->assertSee('Pemindai Barcode');
    }
}
