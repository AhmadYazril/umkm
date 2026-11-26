<?php

namespace Tests\Feature;

use App\Http\Controllers\KasirController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirZendoAndEditOrderTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsKasirAndGetCookie(): string
    {
        KasirController::ensureDefaultKasirUser();
        $loginResponse = $this->post(route('kasir.login.post'), [
            'password' => 'password123',
        ]);
        $loginResponse->assertRedirect(route('kasir.index'));

        $cookieValue = $loginResponse->getCookie(KasirController::KASIR_COOKIE, false)->getValue();
        auth()->logout();

        return $cookieValue;
    }

    /**
     * @return array{cheesecake: Menu, nasiGoreng: Menu, tiramisu: Menu, outOfStock: Menu}
     */
    private function seedSampleMenus(): array
    {
        $dessertCat = Category::create([
            'name' => 'Dessert',
            'slug' => 'dessert',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $mealCat = Category::create([
            'name' => 'Makanan',
            'slug' => 'makanan',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        return [
            'cheesecake' => Menu::create([
                'category_id' => $dessertCat->id,
                'name' => 'Cheesecake',
                'slug' => 'cheesecake',
                'price' => 27000,
                'stock' => 10,
                'is_available' => true,
            ]),
            'nasiGoreng' => Menu::create([
                'category_id' => $mealCat->id,
                'name' => 'Nasi Goreng Kampung',
                'slug' => 'nasi-goreng-kampung',
                'price' => 30000,
                'stock' => 10,
                'is_available' => true,
            ]),
            'tiramisu' => Menu::create([
                'category_id' => $dessertCat->id,
                'name' => 'Tiramisu',
                'slug' => 'tiramisu',
                'price' => 28000,
                'stock' => 10,
                'is_available' => true,
            ]),
            'outOfStock' => Menu::create([
                'category_id' => $dessertCat->id,
                'name' => 'Banoffee Habis',
                'slug' => 'banoffee-habis',
                'price' => 28000,
                'stock' => 0,
                'is_available' => false,
            ]),
        ];
    }

    public function test_zendo_order_enters_pending_and_is_clearly_distinguished_from_dine_in_and_take_away_at_kasir(): void
    {
        Setting::set('order_mode_dine_in', '1');
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('order_mode_zendo_tracking', '0');
        Setting::set('zendo_tier_0_3', '10000');

        $menus = $this->seedSampleMenus();

        // 1. Customer places Zendo order
        $zendoRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Rani Zendo',
            'customer_phone' => '081234567801',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Mayor Sujadi No. 88, Tulungagung',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 1,
                    'line_total' => 27000,
                ],
                [
                    'menu_id' => $menus['nasiGoreng']->id,
                    'menu_name' => $menus['nasiGoreng']->name,
                    'unit_price' => 30000,
                    'qty' => 1,
                    'line_total' => 30000,
                ],
            ],
            'total' => 67000,
        ]);

        $zendoRes->assertOk()->assertJson([
            'success' => true,
            'process_method' => 'zendo',
            'subtotal' => 57000,
            'delivery_fee' => 10000,
            'grand_total' => 67000,
        ]);

        $zendoOrder = Order::where('code', $zendoRes->json('order_code'))->with('items')->firstOrFail();
        $this->assertSame('pending', $zendoOrder->status);
        $this->assertSame('unpaid', $zendoOrder->payment_status);
        $this->assertTrue($zendoOrder->isZendo());
        $this->assertSame('Kurir Zendo', $zendoOrder->service_type_label);
        $this->assertTrue($zendoOrder->canBeEditedAtKasir());

        // 2. Customer places Dine-In and Take Away orders
        $dineInRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Budi DineIn',
            'process_method' => 'kasir',
            'dine_type' => 'dine_in',
            'table_no' => '05',
            'items' => [
                [
                    'menu_id' => $menus['tiramisu']->id,
                    'menu_name' => $menus['tiramisu']->name,
                    'unit_price' => 28000,
                    'qty' => 1,
                    'line_total' => 28000,
                ],
            ],
            'total' => 28000,
        ]);
        $dineInRes->assertOk();

        $takeAwayRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Sari TakeAway',
            'process_method' => 'kasir',
            'dine_type' => 'take_away',
            'items' => [
                [
                    'menu_id' => $menus['tiramisu']->id,
                    'menu_name' => $menus['tiramisu']->name,
                    'unit_price' => 28000,
                    'qty' => 1,
                    'line_total' => 28000,
                ],
            ],
            'total' => 28000,
        ]);
        $takeAwayRes->assertOk();

        // 3. Kasir page & sync & lookup distinguish Kurir Zendo, Makan di Sini, and Bawa Pulang
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        $kasirPage = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.index', ['tab' => 'orders']));
        $kasirPage->assertOk();
        $kasirPage->assertSee($zendoOrder->code);
        $kasirPage->assertSee('Kurir Zendo');
        $kasirPage->assertSee('Makan di Sini');
        $kasirPage->assertSee('Bawa Pulang');
        $kasirPage->assertSee('Belum Bayar');
        $kasirPage->assertSee('Selesai');
        $kasirPage->assertSee('Batal');
        $kasirPage->assertDontSee('data-ord-filter="processing"', false);
        $kasirPage->assertDontSee('data-ord-filter="ready"', false);
        $kasirPage->assertSee('Jl. Mayor Sujadi No. 88, Tulungagung');

        $syncRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->getJson(route('kasir.sync'));
        $syncRes->assertOk();
        $this->assertSame(3, $syncRes->json('stats.pending_orders_count'));

        $syncedByCode = collect($syncRes->json('recent_orders'))->keyBy('code');
        $this->assertTrue($syncedByCode[$zendoOrder->code]['is_zendo']);
        $this->assertSame('zendo', $syncedByCode[$zendoOrder->code]['service_type_key']);
        $this->assertSame('Kurir Zendo', $syncedByCode[$zendoOrder->code]['service_type_label']);
        $this->assertSame(10000, $syncedByCode[$zendoOrder->code]['delivery_fee']);
        $this->assertTrue($syncedByCode[$zendoOrder->code]['can_edit']);

        $this->assertFalse($syncedByCode[$dineInRes->json('order_code')]['is_zendo']);
        $this->assertSame('dine_in', $syncedByCode[$dineInRes->json('order_code')]['service_type_key']);
        $this->assertSame('Makan di Sini', $syncedByCode[$dineInRes->json('order_code')]['service_type_label']);

        $this->assertFalse($syncedByCode[$takeAwayRes->json('order_code')]['is_zendo']);
        $this->assertSame('take_away', $syncedByCode[$takeAwayRes->json('order_code')]['service_type_key']);
        $this->assertSame('Bawa Pulang', $syncedByCode[$takeAwayRes->json('order_code')]['service_type_label']);

        $lookupRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->getJson(route('kasir.orders.lookup', ['code' => $zendoOrder->code]));
        $lookupRes->assertOk()->assertJsonPath('order.service_type_label', 'Kurir Zendo')
            ->assertJsonPath('order.is_zendo', true)
            ->assertJsonPath('order.can_edit', true)
            ->assertJsonPath('order.delivery_fee', 10000);
    }

    public function test_kasir_can_edit_pending_zendo_order_items_adjust_stock_and_keep_delivery_fee(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('zendo_tier_0_3', '10000');

        $menus = $this->seedSampleMenus();

        // Customer initially orders Cheesecake x1 + Nasi Goreng x1 via Zendo (stocks: 10 -> 9 each)
        $orderRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Dina Pelanggan',
            'customer_phone' => '081211112222',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Diponegoro No. 14, Tulungagung',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 1,
                    'line_total' => 27000,
                ],
                [
                    'menu_id' => $menus['nasiGoreng']->id,
                    'menu_name' => $menus['nasiGoreng']->name,
                    'unit_price' => 30000,
                    'qty' => 1,
                    'line_total' => 30000,
                ],
            ],
            'total' => 67000,
        ]);
        $orderRes->assertOk();

        $order = Order::where('code', $orderRes->json('order_code'))->firstOrFail();
        $this->assertSame(9, $menus['cheesecake']->fresh()->stock);
        $this->assertSame(9, $menus['nasiGoreng']->fresh()->stock);
        $this->assertSame(10, $menus['tiramisu']->fresh()->stock);

        // Courier arrives and asks to swap Cheesecake -> Tiramisu x1 and increase Nasi Goreng -> x2
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        $editRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $order->id), [
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                    [
                        'menu_id' => $menus['nasiGoreng']->id,
                        'menu_name' => $menus['nasiGoreng']->name,
                        'unit_price' => 30000,
                        'qty' => 2,
                    ],
                ],
            ]);

        $editRes->assertOk()->assertJson([
            'success' => true,
            'order' => [
                'code' => $order->code,
                'is_zendo' => true,
                'service_type_label' => 'Kurir Zendo',
                'subtotal' => 88000,
                'delivery_fee' => 10000,
                'total' => 98000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ],
        ]);

        // Verify stocks adjusted accurately: Cheesecake restored 9->10, Tiramisu 10->9, Nasi Goreng 9->8
        $this->assertSame(10, $menus['cheesecake']->fresh()->stock);
        $this->assertSame(9, $menus['tiramisu']->fresh()->stock);
        $this->assertSame(8, $menus['nasiGoreng']->fresh()->stock);

        // Verify Zendo metadata preserved in DB and thermal receipt reflects revised items & Zendo label
        $order->refresh();
        $this->assertTrue($order->isZendo());
        $this->assertSame(88000, $order->subtotal);
        $this->assertSame(98000, $order->total);

        $receiptRes = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $order->id));
        $receiptRes->assertOk();
        $receiptRes->assertSee('KURIR ZENDO');
        $receiptRes->assertSee('Tiramisu');
        $receiptRes->assertSee('Nasi Goreng Kampung');
        $receiptRes->assertDontSee('Cheesecake');
        $receiptRes->assertSee('ONGKIR ZENDO');
        $receiptRes->assertSee('Rp 98.000');

        // Now courier pays and kasir saves with mark_completed_paid = true
        $payEditRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $order->id), [
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                ],
                'mark_completed_paid' => true,
            ]);

        $payEditRes->assertOk()->assertJsonPath('order.status', 'completed')
            ->assertJsonPath('order.kasir_bucket', 'completed')
            ->assertJsonPath('order.payment_status', 'paid')
            ->assertJsonPath('order.can_edit', false)
            ->assertJsonPath('order.total', 38000);

        // Stock of Nasi Goreng x2 restored (8 -> 10), Tiramisu remains 9
        $this->assertSame(10, $menus['nasiGoreng']->fresh()->stock);
        $this->assertSame(9, $menus['tiramisu']->fresh()->stock);
    }

    public function test_kasir_edit_order_rejects_unauthenticated_locked_empty_or_out_of_stock_requests(): void
    {
        $menus = $this->seedSampleMenus();

        $pendingOrder = Order::create([
            'code' => 'NCM-20261123-101',
            'customer_name' => 'Pelanggan Pending',
            'customer_phone' => '081200000001',
            'order_type' => 'take_away',
            'delivery_address' => 'Jl. Pahlawan No. 1',
            'delivery_fee' => 10000,
            'notes' => '[KURIR ZENDO] Alamat: Jl. Pahlawan No. 1',
            'subtotal' => 27000,
            'total' => 37000,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);
        $pendingOrder->items()->create([
            'menu_id' => $menus['cheesecake']->id,
            'menu_name' => $menus['cheesecake']->name,
            'unit_price' => 27000,
            'qty' => 1,
            'line_total' => 27000,
        ]);

        // 1. Unauthenticated JSON request is rejected with 401
        $this->putJson(route('kasir.orders.updateItems', $pendingOrder->id), [
            'items' => [
                ['menu_id' => $menus['tiramisu']->id, 'menu_name' => 'Tiramisu', 'unit_price' => 28000, 'qty' => 1],
            ],
        ])->assertStatus(401);

        $kasirCookie = $this->loginAsKasirAndGetCookie();

        // 2. Empty items rejected with 422
        $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $pendingOrder->id), [
                'items' => [],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('items');

        // 3. Adding out-of-stock menu rejected with 422
        $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $pendingOrder->id), [
                'items' => [
                    [
                        'menu_id' => $menus['outOfStock']->id,
                        'menu_name' => $menus['outOfStock']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                ],
            ])
            ->assertStatus(422)
            ->assertJson(['success' => false]);

        // 4. Paid / processing order cannot be edited
        $pendingOrder->update(['status' => 'processing', 'payment_status' => 'paid']);

        $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $pendingOrder->id), [
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                ],
            ])
            ->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_cancelling_pending_order_restores_stock_and_keeps_payment_unpaid(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        $menus = $this->seedSampleMenus();

        $orderRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Batal Zendo',
            'customer_phone' => '081233334444',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Ahmad Yani No. 2',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 2,
                    'line_total' => 54000,
                ],
            ],
            'total' => 64000,
        ]);
        $orderRes->assertOk();

        $order = Order::where('code', $orderRes->json('order_code'))->firstOrFail();
        $this->assertSame(8, $menus['cheesecake']->fresh()->stock);

        $kasirCookie = $this->loginAsKasirAndGetCookie();

        $cancelRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.status', $order->id), [
                'status' => 'cancelled',
            ]);

        $cancelRes->assertOk()
            ->assertJsonPath('order.status', 'cancelled')
            ->assertJsonPath('order.payment_status', 'unpaid');

        // Stock restored back to 10
        $this->assertSame(10, $menus['cheesecake']->fresh()->stock);
        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }

    public function test_customer_dine_in_and_take_away_checkout_saves_chosen_payment_method_qris_and_transfer(): void
    {
        Setting::set('order_mode_dine_in', '1');
        $menus = $this->seedSampleMenus();

        $qrisRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Nadia QRIS',
            'process_method' => 'kasir',
            'dine_type' => 'dine_in',
            'table_no' => '07',
            'payment_method' => 'qris',
            'items' => [
                [
                    'menu_id' => $menus['tiramisu']->id,
                    'menu_name' => $menus['tiramisu']->name,
                    'unit_price' => 28000,
                    'qty' => 1,
                    'line_total' => 28000,
                ],
            ],
            'total' => 28000,
        ]);
        $qrisRes->assertOk();

        $qrisOrder = Order::where('code', $qrisRes->json('order_code'))->firstOrFail();
        $this->assertSame('qris', $qrisOrder->payment_method);
        $this->assertSame('QRIS', $qrisOrder->payment_method_label);
        $this->assertSame('pending', $qrisOrder->status);
        $this->assertSame('unpaid', $qrisOrder->payment_status);

        $transferRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Raka Transfer',
            'process_method' => 'kasir',
            'dine_type' => 'take_away',
            'payment_method' => 'transfer',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 1,
                    'line_total' => 27000,
                ],
            ],
            'total' => 27000,
        ]);
        $transferRes->assertOk();

        $transferOrder = Order::where('code', $transferRes->json('order_code'))->firstOrFail();
        $this->assertSame('transfer', $transferOrder->payment_method);
        $this->assertSame('Transfer', $transferOrder->payment_method_label);
    }

    public function test_kasir_pos_paid_order_is_immediately_completed_without_intermediate_statuses(): void
    {
        $menus = $this->seedSampleMenus();
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        $posRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.store'), [
                'customer_name' => 'Tamu Kasir',
                'order_type' => 'dine_in',
                'table_no' => '02',
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 2,
                    ],
                ],
            ]);

        $posRes->assertOk()
            ->assertJsonPath('order.status', 'completed')
            ->assertJsonPath('order.kasir_bucket', 'completed')
            ->assertJsonPath('order.payment_status', 'paid')
            ->assertJsonPath('order.payment_method', 'qris')
            ->assertJsonPath('order.change_amount', 0);

        $order = Order::findOrFail($posRes->json('order.id'));
        $this->assertSame('completed', $order->status);
        $this->assertSame('paid', $order->payment_status);
    }

    public function test_kasir_completes_zendo_order_with_cash_calculates_change_against_subtotal_and_rejects_underpayment(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('zendo_tier_0_3', '10000');

        $menus = $this->seedSampleMenus();

        // Zendo order: subtotal 57.000, delivery_fee 10.000, total 67.000
        $zendoRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Bayu Zendo',
            'customer_phone' => '081299998888',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Supriadi No. 12, Tulungagung',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 1,
                    'line_total' => 27000,
                ],
                [
                    'menu_id' => $menus['nasiGoreng']->id,
                    'menu_name' => $menus['nasiGoreng']->name,
                    'unit_price' => 30000,
                    'qty' => 1,
                    'line_total' => 30000,
                ],
            ],
            'total' => 67000,
        ]);
        $zendoRes->assertOk();

        $order = Order::where('code', $zendoRes->json('order_code'))->firstOrFail();
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        // 1. Underpayment below subtotal (50.000 < 57.000) is rejected with 422
        $underpayRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.status', $order->id), [
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'cash_received' => 50000,
            ]);

        $underpayRes->assertStatus(422)
            ->assertJson(['success' => false]);
        $this->assertSame('pending', $order->fresh()->status);

        // 2. Courier pays Rp 100.000 tunai for subtotal Rp 57.000 -> change Rp 43.000
        $payRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.status', $order->id), [
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'cash_received' => 100000,
            ]);

        $payRes->assertOk()
            ->assertJson([
                'success' => true,
                'payable_amount' => 57000,
                'cash_received' => 100000,
                'change_amount' => 43000,
            ])
            ->assertJsonPath('order.status', 'completed')
            ->assertJsonPath('order.payment_status', 'paid')
            ->assertJsonPath('order.payment_method', 'cash')
            ->assertJsonPath('order.kasir_bucket', 'completed');
    }

    public function test_kasir_completes_order_with_qris_or_transfer_without_requiring_cash_change(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('zendo_tier_0_3', '10000');

        $menus = $this->seedSampleMenus();

        $zendoRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Sinta Zendo QRIS',
            'customer_phone' => '081277776666',
            'process_method' => 'zendo',
            'delivery_address' => 'Jl. Panglima Sudirman No. 9',
            'items' => [
                [
                    'menu_id' => $menus['tiramisu']->id,
                    'menu_name' => $menus['tiramisu']->name,
                    'unit_price' => 28000,
                    'qty' => 1,
                    'line_total' => 28000,
                ],
            ],
            'total' => 38000,
        ]);
        $zendoRes->assertOk();

        $order = Order::where('code', $zendoRes->json('order_code'))->firstOrFail();
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        // Courier chooses QRIS at cafe -> completed directly without cash_received / change
        $qrisPayRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.status', $order->id), [
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
            ]);

        $qrisPayRes->assertOk()
            ->assertJson([
                'success' => true,
                'payable_amount' => 28000,
                'change_amount' => 0,
            ])
            ->assertJsonPath('order.status', 'completed')
            ->assertJsonPath('order.payment_status', 'paid')
            ->assertJsonPath('order.payment_method', 'qris')
            ->assertJsonPath('order.payment_method_label', 'QRIS')
            ->assertJsonPath('order.kasir_bucket', 'completed');

        $order->refresh();
        $this->assertSame('completed', $order->status);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('qris', $order->payment_method);
    }

    public function test_legacy_unpaid_processing_zendo_order_can_still_be_edited_and_preserves_inferred_delivery_fee(): void
    {
        $menus = $this->seedSampleMenus();

        // Simulate an older Zendo order created when status defaulted to 'processing' and delivery_fee column was 0
        $legacyZendo = Order::create([
            'code' => 'NCM-20261025-003',
            'customer_name' => 'Krucel Legacy',
            'customer_phone' => 'Via Zendo WA',
            'order_type' => 'take_away',
            'notes' => '[KURIR ZENDO] Alamat: Jl. Merdeka No. 3',
            'delivery_address' => 'Jl. Merdeka No. 3',
            'delivery_fee' => 0,
            'subtotal' => 27000,
            'total' => 42000,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'status' => 'processing',
        ]);
        $legacyZendo->items()->create([
            'menu_id' => $menus['cheesecake']->id,
            'menu_name' => $menus['cheesecake']->name,
            'unit_price' => 27000,
            'qty' => 1,
            'line_total' => 27000,
        ]);

        $this->assertTrue($legacyZendo->canBeEditedAtKasir());

        $kasirCookie = $this->loginAsKasirAndGetCookie();

        // Lookup via Scan QR exposes can_edit=true and inferred delivery_fee=15000
        $lookupRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->getJson(route('kasir.orders.lookup', ['code' => $legacyZendo->code]));

        $lookupRes->assertOk()
            ->assertJsonPath('order.can_edit', true)
            ->assertJsonPath('order.kasir_bucket', 'unpaid')
            ->assertJsonPath('order.subtotal', 27000)
            ->assertJsonPath('order.delivery_fee', 15000)
            ->assertJsonPath('order.payable_amount', 27000)
            ->assertJsonPath('order.total', 42000);

        // Kasir edits item from Cheesecake (27k) -> Tiramisu (28k); inferred 15k delivery fee is preserved -> total 43k
        $editRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->putJson(route('kasir.orders.updateItems', $legacyZendo->id), [
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                ],
            ]);

        $editRes->assertOk()
            ->assertJsonPath('order.subtotal', 28000)
            ->assertJsonPath('order.delivery_fee', 15000)
            ->assertJsonPath('order.payable_amount', 28000)
            ->assertJsonPath('order.total', 43000)
            ->assertJsonPath('order.can_edit', true);

        $legacyZendo->refresh();
        $this->assertSame(15000, $legacyZendo->delivery_fee);
        $this->assertSame(28000, $legacyZendo->subtotal);
        $this->assertSame(43000, $legacyZendo->total);
    }

    public function test_receipt_displays_product_total_cafe_total_payment_and_change_with_zendo_fee_separated(): void
    {
        Setting::set('order_mode_zendo_chat', '1');
        Setting::set('zendo_tier_0_3', '12000');

        $menus = $this->seedSampleMenus();
        $kasirCookie = $this->loginAsKasirAndGetCookie();

        // 1. Zendo order: Cheesecake x1 (Rp 27.000) + Ongkir Zendo (Rp 12.000) -> Customer total Rp 39.000
        $zendoRes = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Al Zendo',
            'customer_phone' => '081255554444',
            'process_method' => 'zendo',
            'delivery_address' => 'Kepatihan, Ringinpitu, Tulungagung',
            'items' => [
                [
                    'menu_id' => $menus['cheesecake']->id,
                    'menu_name' => $menus['cheesecake']->name,
                    'unit_price' => 27000,
                    'qty' => 1,
                    'line_total' => 27000,
                ],
            ],
            'total' => 39000,
        ]);
        $zendoRes->assertOk();

        $zendoOrder = Order::where('code', $zendoRes->json('order_code'))->firstOrFail();

        // Courier pays Rp 50.000 cash at cafe for Rp 27.000 menu -> change Rp 23.000
        $payZendoRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.status', $zendoOrder->id), [
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'cash_received' => 50000,
            ]);
        $payZendoRes->assertOk()
            ->assertJsonPath('payable_amount', 27000)
            ->assertJsonPath('cash_received', 50000)
            ->assertJsonPath('change_amount', 23000);

        $zendoReceipt = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $zendoOrder->id));
        $zendoReceipt->assertOk();
        $zendoReceipt->assertSee('TOTAL PRODUK (1 ITEM):');
        $zendoReceipt->assertSee('TOTAL BAYAR KAFE:');
        $zendoReceipt->assertSee('Rp 27.000');
        $zendoReceipt->assertSee('PEMBAYARAN (TUNAI):');
        $zendoReceipt->assertSee('Rp 50.000');
        $zendoReceipt->assertSee('KEMBALIAN:');
        $zendoReceipt->assertSee('Rp 23.000');
        $zendoReceipt->assertSee('INFO KURIR ZENDO (TERPISAH):');
        $zendoReceipt->assertSee('ONGKIR ZENDO:');
        $zendoReceipt->assertSee('Rp 12.000');
        $zendoReceipt->assertSee('EST. TOTAL PELANGGAN:');
        $zendoReceipt->assertSee('Rp 39.000');
        $zendoReceipt->assertDontSee('TOTAL AKHIR:');

        // 2. POS Dine-In Cash Order: Tiramisu x1 (Rp 28.000), paid Rp 50.000 -> change Rp 22.000
        $posRes = $this->withCredentials()
            ->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->postJson(route('kasir.orders.store'), [
                'customer_name' => 'Tamu Meja 4',
                'order_type' => 'dine_in',
                'table_no' => '04',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'cash_received' => 50000,
                'items' => [
                    [
                        'menu_id' => $menus['tiramisu']->id,
                        'menu_name' => $menus['tiramisu']->name,
                        'unit_price' => 28000,
                        'qty' => 1,
                    ],
                ],
            ]);
        $posRes->assertOk();

        $posReceipt = $this->withUnencryptedCookie(KasirController::KASIR_COOKIE, $kasirCookie)
            ->get(route('kasir.orders.receipt', $posRes->json('order.id')));
        $posReceipt->assertOk();
        $posReceipt->assertSee('TOTAL PRODUK (1 ITEM):');
        $posReceipt->assertSee('TOTAL AKHIR:');
        $posReceipt->assertSee('Rp 28.000');
        $posReceipt->assertSee('PEMBAYARAN (TUNAI):');
        $posReceipt->assertSee('Rp 50.000');
        $posReceipt->assertSee('KEMBALIAN:');
        $posReceipt->assertSee('Rp 22.000');
        $posReceipt->assertDontSee('INFO KURIR ZENDO');
    }
}
