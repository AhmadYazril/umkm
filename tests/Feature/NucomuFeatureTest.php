<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NucomuFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_nucomu_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Nucomu Cafe');
        $response->assertSee('BEST MENU');
        $response->assertSee('OUR MENU CATEGORIES');
        $response->assertSee('Layanan');
        $response->assertSee('Pemesanan');
        $response->assertSee('product-showcase');
        $response->assertSee('Es Kopi Susu Gula Aren');
        $response->assertSee('showcase.css');
        $response->assertSee('showcase.js');
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
}
