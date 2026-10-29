<?php

namespace Tests\Feature;

use App\Http\Controllers\NucomuController;
use App\Http\Controllers\ZendoTrackingController;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\User;
use App\Models\ZendoDelivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NucomuFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_nucomu_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Nucomu Cafe');
        $response->assertSee('OUR STORY / TENTANG KAMI');
        $response->assertSee('Berawal dari Rasa,');
        $response->assertSee('BEST MENU');
        $response->assertSee('OUR MENU CATEGORIES');
        $response->assertSee('Layanan');
        $response->assertSee('Pemesanan');
        $response->assertSee('Basque Burnt Cheesecake');
        $response->assertSee('nucomu.css');
        $response->assertSee('nucomu.js');
    }

    public function test_nucomu_all_menu_page_loads_successfully(): void
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Katalog Sajian Nucomu');
        $response->assertSee('Semua Menu');
    }

    public function test_nucomu_reservation_submission_saves_to_database_and_returns_whatsapp_url(): void
    {
        $payload = [
            'fullName' => 'Siti Aminah',
            'phone' => '081234567890',
            'date' => '2026-10-30',
            'time' => '14:30',
            'guestCount' => '4 Orang',
            'serviceType' => 'Birthday Treats',
            'area' => 'Indoor (AC & Working Space)',
            'notes' => 'Meja pojok yang tenang',
        ];

        $response = $this->postJson('/reservasi', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'whatsapp_url',
            'data',
        ]);

        $this->assertDatabaseHas('reservations', [
            'name' => 'Siti Aminah',
            'phone' => '081234567890',
            'status' => 'pending',
        ]);

        $created = Reservation::where('phone', '081234567890')->first();
        $this->assertNotNull($created);
        $this->assertEquals(4, $created->guests);
    }

    public function test_google_maps_reviews_and_website_reviews_both_appear_on_web(): void
    {
        $this->post(route('nucomu.review.store'), [
            'name' => 'Rara Pelanggan Web',
            'handle' => '@rara_web',
            'order_tag' => 'Nucomu Signature Latte',
            'rating' => 5,
            'content' => 'Ulasan dari form website langsung tampil di web.',
        ])->assertRedirect();

        $this->assertDatabaseHas('testimonials', [
            'name' => 'Rara Pelanggan Web',
            'source' => 'website',
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        // Website review appears
        $response->assertSee('Rara Pelanggan Web');
        // All Google Maps reviews also appear unified on web
        $response->assertSee('Dya slma');
        $response->assertSee('Febry Hassan');
        $response->assertSee('Karina Wulan Cahyani');
        $response->assertSee('Muhammad Kaisi');
        $response->assertSee('Tani Kopi');
        $response->assertSee('Aulia Rahma');
    }

    public function test_reservation_form_displays_bridal_and_birthday_decoration_packages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Paket Acara Bridal');
        $response->assertSee('Paket Acara Birthday');
        $response->assertSee('Paket Dekorasi A');
        $response->assertSee('35K');
        $response->assertSee('Paket Dekorasi B');
        $response->assertSee('60K');
        $response->assertSee('Paket Dekorasi C');
        $response->assertSee('100K');
    }

    public function test_order_transaction_supports_kasir_zendo_and_both_modes(): void
    {
        Setting::set('transaction_mode', 'both');
        Setting::set('zendo_courier_count', '2');
        Setting::set('zendo_courier_1_name', 'Kurir Zendo 1');
        Setting::set('zendo_courier_1_phone', '085712345678');
        Setting::set('zendo_courier_1_status', 'idle');
        Setting::set('zendo_courier_2_name', 'Kurir Zendo 2');
        Setting::set('zendo_courier_2_phone', '081398765432');
        Setting::set('zendo_courier_2_status', 'idle');

        $orderPage = $this->get(route('nucomu.order'));
        $orderPage->assertStatus(200);
        $orderPage->assertSee('Kurir Zendo');
        $orderPage->assertSee('2 Kurir Siaga');
        $orderPage->assertSee('Kasir');

        // Order 1: Picks randomly between idle Courier 1 and Courier 2
        $zendoResponse1 = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Andi Zendo 1',
            'customer_phone' => '081299998888',
            'delivery_address' => 'Jl. Basuki Rahmat No. 12, Tulungagung',
            'distance_km' => 4.5,
            'customer_lat' => -8.0995,
            'customer_lng' => 111.8965,
            'process_method' => 'zendo',
            'notes' => 'Less ice',
            'items' => [
                [
                    'menu_id' => 1,
                    'menu_name' => 'Nucomu Signature Coffee',
                    'unit_price' => 24000,
                    'qty' => 2,
                    'line_total' => 48000,
                ],
            ],
            'total' => 63000,
        ]);

        $zendoResponse1->assertStatus(200);
        $zendoResponse1->assertJson([
            'success' => true,
            'process_method' => 'zendo',
            'dispatch_reason' => 'random_idle',
            'delivery_fee' => 15000,
            'grand_total' => 63000,
            'slip_url' => null,
        ]);

        $firstAssignedPhone = $zendoResponse1->json('zendo_contact');
        $this->assertContains($firstAssignedPhone, ['6285712345678', '6281398765432']);

        // Order 2: Automatically goes to the remaining idle courier who has not received an order yet
        $zendoResponse2 = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Budi Zendo 2',
            'customer_phone' => '081277776666',
            'delivery_address' => 'Jl. Pahlawan No. 8, Tulungagung',
            'distance_km' => 2.0,
            'process_method' => 'zendo',
            'items' => [
                [
                    'menu_id' => 1,
                    'menu_name' => 'Nucomu Signature Coffee',
                    'unit_price' => 24000,
                    'qty' => 1,
                    'line_total' => 24000,
                ],
            ],
            'total' => 34000,
        ]);

        $zendoResponse2->assertStatus(200);
        $secondAssignedPhone = $zendoResponse2->json('zendo_contact');
        $this->assertNotEquals($firstAssignedPhone, $secondAssignedPhone);
        $this->assertSame('random_idle', $zendoResponse2->json('dispatch_reason'));

        // Now BOTH couriers have active orders. Mark Courier 1 as "Hampir Selesai Pengiriman" (almost_done)
        Setting::set('zendo_courier_1_status', 'almost_done');

        // Order 3: Since all couriers have orders, it must route to Courier 1 (almost_done)
        $zendoResponse3 = $this->postJson(route('nucomu.order.store'), [
            'customer_name' => 'Citra Zendo 3',
            'customer_phone' => '081255554444',
            'delivery_address' => 'Jl. Diponegoro No. 45, Tulungagung',
            'distance_km' => 3.0,
            'process_method' => 'zendo',
            'items' => [
                [
                    'menu_id' => 1,
                    'menu_name' => 'Nucomu Signature Coffee',
                    'unit_price' => 24000,
                    'qty' => 1,
                    'line_total' => 24000,
                ],
            ],
            'total' => 34000,
        ]);

        $zendoResponse3->assertStatus(200);
        $zendoResponse3->assertJson([
            'success' => true,
            'courier_name' => 'Kurir Zendo 1',
            'zendo_contact' => '6285712345678',
            'dispatch_reason' => 'almost_done',
        ]);

        $controller = app(NucomuController::class);
        $tariff = [
            'tier_0_3' => 10000,
            'tier_3_4' => 12000,
            'tier_4_5' => 15000,
            'tier_5_6' => 17000,
            'tier_6_7' => 18000,
            'extra_per_km' => 2500,
        ];
        $this->assertSame(10000, $controller->calculateZendoDeliveryFee(2.5, $tariff));
        $this->assertSame(12000, $controller->calculateZendoDeliveryFee(3.5, $tariff));
        $this->assertSame(15000, $controller->calculateZendoDeliveryFee(4.5, $tariff));
        $this->assertSame(17000, $controller->calculateZendoDeliveryFee(5.5, $tariff));
        $this->assertSame(18000, $controller->calculateZendoDeliveryFee(6.5, $tariff));
        $this->assertSame(23000, $controller->calculateZendoDeliveryFee(8.4, $tariff));
    }

    public function test_zendo_tracking_page_renders_grab_like_live_map_and_provides_json_stream(): void
    {
        $delivery = ZendoDelivery::factory()->create([
            'order_code' => 'NCM-20261026-001',
            'courier_name' => 'Kurir Zendo 1',
            'courier_phone' => '6285712345678',
            'status' => 'picked_up',
            'courier_lat' => -8.0770,
            'courier_lng' => 111.9190,
            'courier_accuracy' => 12.0,
            'destination_lat' => -8.0850,
            'destination_lng' => 111.9250,
            'delivery_address' => 'Jl. Pahlawan No. 10, Tulungagung',
        ]);

        // 1. Web View
        $viewResponse = $this->get(route('zendo.track', $delivery->tracking_token));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Nucomu Cafe x Zendo');
        $viewResponse->assertSee('#NCM-20261026-001');
        $viewResponse->assertSee('Kurir Zendo 1');
        $viewResponse->assertSee('Jl. Pahlawan No. 10');

        // 2. Data Endpoint
        $dataResponse = $this->getJson(route('zendo.track.data', $delivery->tracking_token));
        $dataResponse->assertStatus(200);
        $dataResponse->assertJson([
            'order_code' => 'NCM-20261026-001',
            'status' => 'picked_up',
            'courier' => [
                'name' => 'Kurir Zendo 1',
                'phone' => '6285712345678',
            ],
            'destination' => [
                'address' => 'Jl. Pahlawan No. 10, Tulungagung',
            ],
        ]);
        $this->assertNotNull($dataResponse->json('remaining_m'));
        $this->assertNotNull($dataResponse->json('eta_minutes'));
    }

    public function test_zendo_courier_link_is_strictly_gated_by_courier_phone_number(): void
    {
        $delivery = ZendoDelivery::factory()->create([
            'courier_phone' => '6285712345678',
            'courier_name' => 'Kurir Zendo 1',
        ]);

        // 1. Opening the courier link automatically enters Courier Mode without requiring manual phone number input
        $courierShowResponse = $this->get(route('zendo.courier.show', $delivery->courier_token));
        $courierShowResponse->assertRedirect(route('zendo.track', [
            'token' => $delivery->tracking_token,
            'k' => $delivery->courier_token,
        ]));
        $courierShowResponse->assertCookie(ZendoTrackingController::DEVICE_COOKIE);

        $delivery->refresh();
        $this->assertNotNull($delivery->courier_verified_at);

        $deviceCookieValue = $courierShowResponse->getCookie(ZendoTrackingController::DEVICE_COOKIE, false)->getValue();

        // 2. The courier workspace loads in Mode Kurir with GPS streaming and courier action controls
        $courierWorkspaceResponse = $this->withUnencryptedCookie(ZendoTrackingController::DEVICE_COOKIE, $deviceCookieValue)
            ->get(route('zendo.track', [
                'token' => $delivery->tracking_token,
                'k' => $delivery->courier_token,
            ]));
        $courierWorkspaceResponse->assertStatus(200);
        $courierWorkspaceResponse->assertSee('Mode Kurir');
        $courierWorkspaceResponse->assertSee('Kurir Zendo 1');
        $courierWorkspaceResponse->assertSee('6285712345678');
        $courierWorkspaceResponse->assertSee('Tampilan Pelanggan');

        // 3. A customer opening the tracking link (without k) only sees the live map (cuma bisa melihat kurirnya sampai mana)
        $this->defaultCookies = [];
        $this->unencryptedCookies = [];
        $customerResponse = $this->get(route('zendo.track', ['token' => $delivery->tracking_token]));
        $customerResponse->assertStatus(200);
        $customerResponse->assertDontSee('GPS Lock Aktif (Anti Fake GPS)');
        $customerResponse->assertDontSee($delivery->courier_token);
        $customerResponse->assertSee('Live Tracking');
        $customerResponse->assertSee('Kurir Zendo 1');
        $customerResponse->assertSee('Masuk Mode Kurir');

        // 4. The single tracking link can be unlocked to Courier Mode using the 4-digit PIN
        $wrongPinResponse = $this->postJson(route('zendo.track.pin', $delivery->tracking_token), ['pin' => '9999']);
        $wrongPinResponse->assertStatus(422);
        $wrongPinResponse->assertJson(['success' => false]);

        $correctPinResponse = $this->postJson(route('zendo.track.pin', $delivery->tracking_token), ['pin' => '1234']);
        $correctPinResponse->assertStatus(200);
        $correctPinResponse->assertJson(['success' => true]);
        $correctPinResponse->assertCookie(ZendoTrackingController::DEVICE_COOKIE);

        $newCookieValue = $correctPinResponse->getCookie(ZendoTrackingController::DEVICE_COOKIE, false)->getValue();

        // 5. Permanent Access: Once PIN is verified, subsequent orders opened on this device immediately grant Courier Mode
        $secondDelivery = ZendoDelivery::factory()->create([
            'courier_phone' => '6285712345678',
            'courier_name' => 'Kurir Zendo 1',
        ]);
        $secondDeliveryResponse = $this->withUnencryptedCookie(ZendoTrackingController::DEVICE_COOKIE, $newCookieValue)
            ->get(route('zendo.track', ['token' => $secondDelivery->tracking_token]));
        $secondDeliveryResponse->assertStatus(200);
        $secondDeliveryResponse->assertSee('Mode Kurir');
        $secondDeliveryResponse->assertSee('GPS Lock Aktif (Anti Fake GPS)');

        // 6. Admin can update the Courier PIN and the new PIN takes effect
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser)->post(route('admin.zendo.couriers.update'), [
            'zendo_courier_pin' => '7788',
            'zendo_courier_count' => 1,
            'zendo_couriers' => [
                ['name' => 'Kurir Zendo 1', 'phone' => '6285712345678'],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertSame('7788', Setting::get('zendo_courier_pin'));

        // Old PIN 1234 now fails, new PIN 7788 succeeds
        $this->defaultCookies = [];
        $this->unencryptedCookies = [];
        $this->postJson(route('zendo.track.pin', $secondDelivery->tracking_token), ['pin' => '1234'])
            ->assertStatus(422);
        $this->postJson(route('zendo.track.pin', $secondDelivery->tracking_token), ['pin' => '7788'])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        // 7. GPS streaming is accepted for the courier before reset
        $validGps = $this->withHeaders(['X-Courier-Device' => $newCookieValue])
            ->postJson(route('zendo.track.location', $delivery->tracking_token), [
                'lat' => -8.0766,
                'lng' => 111.9188,
                'accuracy' => 10,
                'timestamp' => now()->timestamp * 1000,
            ]);
        $validGps->assertStatus(200);
        $validGps->assertJson(['accepted' => true]);

        // 8. Connected device appears in Admin Zendo table
        $adminZendoPage = $this->actingAs($adminUser)->get(route('admin.zendo.index'));
        $adminZendoPage->assertStatus(200);
        $adminZendoPage->assertSee('Tabel Perangkat Terhubung sebagai Kurir');
        $adminZendoPage->assertSee('Kurir Zendo 1');
        $adminZendoPage->assertSee('Reset / Keluarkan');

        $devices = app(NucomuController::class)->getConnectedDevicesList();
        $this->assertNotEmpty($devices);

        [$cookiePhone, $cookieRawToken] = explode('|', $newCookieValue, 2);
        $cookieTokenHash = hash('sha256', $cookieRawToken);
        $targetDev = collect($devices)->firstWhere('token_hash', $cookieTokenHash) ?? $devices[0];

        // 9. Admin resets/disconnects this specific device
        $resetResponse = $this->actingAs($adminUser)->post(route('admin.zendo.devices.reset', $targetDev['id']));
        $resetResponse->assertRedirect();
        $resetResponse->assertSessionHas('success');

        // Location ping from the reset device is now rejected (kicked out of courier mode)
        $rejectedGps = $this->withHeaders(['X-Courier-Device' => $newCookieValue])
            ->postJson(route('zendo.track.location', $delivery->tracking_token), [
                'lat' => -8.0766,
                'lng' => 111.9188,
                'accuracy' => 10,
                'timestamp' => now()->timestamp * 1000,
            ]);
        $rejectedGps->assertStatus(403);
        $rejectedGps->assertJson(['accepted' => false, 'revoked' => true]);

        // 10. Admin can reset all devices at once
        $resetAllResponse = $this->actingAs($adminUser)->post(route('admin.zendo.devices.resetAll'));
        $resetAllResponse->assertRedirect();
        $resetAllResponse->assertSessionHas('success');

        // 11. Device unbind route works
        $unbindResponse = $this->post(route('zendo.courier.unbind'));
        $unbindResponse->assertCookieExpired(ZendoTrackingController::DEVICE_COOKIE);
    }

    public function test_zendo_courier_gps_lock_validates_real_gps_and_detects_fake_gps(): void
    {
        $delivery = ZendoDelivery::factory()->create([
            'courier_phone' => '6285712345678',
            'courier_name' => 'Kurir Zendo 1',
            'destination_lat' => -8.0850,
            'destination_lng' => 111.9250,
        ]);

        // Authorize device
        $deviceToken = Str::random(48);
        Setting::set("zendo_device_{$delivery->courier_phone}", hash('sha256', $deviceToken));
        $authHeader = ['X-Courier-Device' => "{$delivery->courier_phone}|{$deviceToken}"];

        // 1. Valid GPS Reading
        $validGps = $this->withHeaders($authHeader)->postJson(route('zendo.courier.location', $delivery->courier_token), [
            'lat' => -8.0766,
            'lng' => 111.9188,
            'accuracy' => 10.5,
            'speed' => 5.2,
            'heading' => 90.0,
            'timestamp' => now()->timestamp * 1000,
        ]);
        $validGps->assertStatus(200);
        $validGps->assertJson(['accepted' => true]);

        $delivery->refresh();
        $this->assertEquals(-8.0766, $delivery->courier_lat);

        // 2. Fake GPS with impossible jump / teleportation velocity is rejected
        $fakeGps = $this->withHeaders($authHeader)->postJson(route('zendo.courier.location', $delivery->courier_token), [
            'lat' => -7.2575, // Surabaya (~100km jump in 1 second)
            'lng' => 112.7521,
            'accuracy' => 5.0,
            'timestamp' => now()->timestamp * 1000,
        ]);
        $fakeGps->assertStatus(422);
        $fakeGps->assertJson(['accepted' => false, 'suspicious' => true]);

        $delivery->refresh();
        $this->assertGreaterThan(0, $delivery->gps_suspicious_count);
    }

    public function test_zendo_courier_status_actions_are_proximity_locked(): void
    {
        $cafeLat = (float) config('nucomu.contact.cafe_lat', -8.0765099);
        $cafeLng = (float) config('nucomu.contact.cafe_lng', 111.918712);

        $delivery = ZendoDelivery::factory()->create([
            'courier_phone' => '6285712345678',
            'status' => 'assigned',
            'destination_lat' => -8.0850,
            'destination_lng' => 111.9250,
        ]);

        $deviceToken = Str::random(48);
        Setting::set("zendo_device_{$delivery->courier_phone}", hash('sha256', $deviceToken));
        $authHeader = ['X-Courier-Device' => "{$delivery->courier_phone}|{$deviceToken}"];

        // 1. Attempting pickup while FAR from cafe (> 500m) is rejected
        $delivery->update([
            'courier_lat' => $cafeLat + 0.05, // ~5 km away
            'courier_lng' => $cafeLng + 0.05,
            'courier_accuracy' => 15,
            'location_updated_at' => now(),
        ]);

        $farPickup = $this->withHeaders($authHeader)->postJson(route('zendo.courier.status', $delivery->courier_token), [
            'status' => 'picked_up',
        ]);
        $farPickup->assertStatus(422);
        $farPickup->assertJson(['success' => false]);

        // 2. Attempting pickup while CLOSE to cafe (<= 500m) succeeds
        $delivery->update([
            'courier_lat' => $cafeLat + 0.0005, // ~60m away
            'courier_lng' => $cafeLng + 0.0005,
            'location_updated_at' => now(),
        ]);

        $closePickup = $this->withHeaders($authHeader)->postJson(route('zendo.courier.status', $delivery->courier_token), [
            'status' => 'picked_up',
        ]);
        $closePickup->assertStatus(200);
        $closePickup->assertJson(['success' => true]);

        $delivery->refresh();
        $this->assertSame('picked_up', $delivery->status);
    }
}
