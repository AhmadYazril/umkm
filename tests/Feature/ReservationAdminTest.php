<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\ReservationField;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ReservationAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_reservations_index(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.reservations.index'));

        $response->assertStatus(200);
        $response->assertSee('Reservasi Meja &amp; Formulir Web', false);
        $response->assertSee('Daftar Reservasi Masuk');
        $response->assertSee('Form Builder &amp; Nomor WhatsApp', false);
    }

    public function test_admin_can_update_reservation_status(): void
    {
        $admin = $this->getAdminUser();

        $reservation = Reservation::create([
            'name' => 'Budi Santoso',
            'phone' => '081298765432',
            'date' => '2026-11-10',
            'time' => '16:00',
            'guests' => 4,
            'notes' => 'Dekat colokan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.reservations.updateStatus', $reservation->id), [
            'status' => 'confirmed',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_can_update_target_whatsapp_number_with_auto_normalization(): void
    {
        $admin = $this->getAdminUser();

        // 08... should be normalized to 628...
        $response = $this->actingAs($admin)->post(route('admin.reservations.whatsapp.update'), [
            'whatsapp_number' => '089876543210',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('6289876543210', Setting::get('reservation_whatsapp'));
    }

    public function test_form_builder_can_add_field(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.reservations.fields.store'), [
            'label' => 'Pilihan Kursi Bayi',
            'type' => 'text',
            'placeholder' => 'Contoh: 1 kursi bayi',
            'is_required' => 0,
            'sort_order' => 10,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservation_fields', [
            'label' => 'Pilihan Kursi Bayi',
            'type' => 'text',
            'is_required' => false,
        ]);
    }

    public function test_form_builder_validates_checkbox_requires_at_least_two_options(): void
    {
        $admin = $this->getAdminUser();

        // Checkbox with only 1 option -> must fail
        $response = $this->actingAs($admin)->post(route('admin.reservations.fields.store'), [
            'label' => 'Preferensi Suasana',
            'type' => 'checkbox',
            'checkbox_options' => 'Hanya Satu Pilihan',
        ]);

        $response->assertSessionHasErrors(['checkbox_options']);

        // Checkbox with 2+ options -> must succeed
        $responseSuccess = $this->actingAs($admin)->post(route('admin.reservations.fields.store'), [
            'label' => 'Preferensi Suasana',
            'type' => 'checkbox',
            'checkbox_options' => "Romantis\nTenang & Nyaman\nDekat Taman",
            'is_required' => 1,
        ]);

        $responseSuccess->assertSessionHas('success');
        $this->assertDatabaseHas('reservation_fields', [
            'label' => 'Preferensi Suasana',
            'type' => 'checkbox',
            'is_required' => true,
        ]);
    }

    public function test_form_builder_can_update_and_toggle_field(): void
    {
        $admin = $this->getAdminUser();

        $field = ReservationField::create([
            'label' => 'Catatan Tambahan',
            'field_key' => 'catatan_khusus',
            'type' => 'textarea',
            'placeholder' => 'Tulis di sini',
            'is_required' => false,
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Toggle
        $this->actingAs($admin)->post(route('admin.reservations.fields.toggle', $field->id));
        $this->assertFalse((bool) $field->fresh()->is_active);

        // Update
        $this->actingAs($admin)->put(route('admin.reservations.fields.update', $field->id), [
            'label' => 'Catatan Khusus & Acuan',
            'type' => 'textarea',
            'placeholder' => 'Harap sediakan lilin',
            'is_required' => 1,
            'sort_order' => 6,
        ]);

        $updated = $field->fresh();
        $this->assertEquals('Catatan Khusus & Acuan', $updated->label);
        $this->assertTrue((bool) $updated->is_required);
    }

    public function test_customer_can_submit_reservation_with_dynamic_fields(): void
    {
        Setting::set('reservation_whatsapp', '6281234567890');

        $response = $this->post(route('reservation.store'), [
            'name' => 'Khanzahamida',
            'phone' => '081234567899',
            'date' => '2026-11-20',
            'time' => '17:00',
            'guests' => 3,
            'notes' => 'Meja pojok lantai 2',
        ]);

        $this->assertDatabaseHas('reservations', [
            'name' => 'Khanzahamida',
            'phone' => '081234567899',
            'guests' => 3,
            'status' => 'pending',
        ]);

        // Reservation should redirect away to WhatsApp
        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('https://wa.me/6281234567890', $redirectUrl);
        $this->assertStringContainsString(rawurlencode('Khanzahamida'), $redirectUrl);
    }

    public function test_photo_upload_validation_and_link_generation(): void
    {
        // Add a photo field
        $photoField = ReservationField::create([
            'label' => 'Foto Acuan Dekorasi',
            'field_key' => 'foto_dekorasi',
            'type' => 'photo',
            'is_required' => true,
            'sort_order' => 7,
            'is_active' => true,
        ]);

        // 1. Rejects file larger than 2MB
        $hugeFile = UploadedFile::fake()->create('huge.jpg', 2500, 'image/jpeg');
        $responseHuge = $this->post(route('reservation.store'), [
            'name' => 'Rina',
            'phone' => '081234567899',
            'date' => '2026-11-20',
            'time' => '17:00',
            'guests' => 2,
            'foto_dekorasi' => $hugeFile,
        ]);
        $responseHuge->assertSessionHasErrors();

        // 2. Accepts valid 500 KB PNG file
        $validFile = UploadedFile::fake()->create('dekor.png', 500, 'image/png');
        $responseOk = $this->post(route('reservation.store'), [
            'name' => 'Rina Sukma',
            'phone' => '081234567899',
            'date' => '2026-11-20',
            'time' => '17:00',
            'guests' => 2,
            'foto_dekorasi' => $validFile,
        ]);

        $responseOk->assertStatus(302);
        $redirectUrl = $responseOk->headers->get('Location');
        $this->assertStringContainsString('uploads%2Freservations%2Fres_foto_dekorasi', $redirectUrl);

        $savedReservation = Reservation::where('name', 'Rina Sukma')->first();
        $this->assertNotNull($savedReservation);
        $this->assertIsArray($savedReservation->form_data);

        // Find photo item
        $photoItem = collect($savedReservation->form_data)->firstWhere('key', 'foto_dekorasi');
        $this->assertNotNull($photoItem);
        $this->assertNotNull($photoItem['url']);
        $this->assertStringContainsString('uploads/reservations/', $photoItem['path']);

        // Check file exists on disk
        $filePath = public_path($photoItem['path']);
        $this->assertFileExists($filePath);

        // Cleanup: destroy reservation should delete file
        $admin = $this->getAdminUser();
        $this->actingAs($admin)->delete(route('admin.reservations.destroy', $savedReservation->id));
        $this->assertFileDoesNotExist($filePath);
    }
}
