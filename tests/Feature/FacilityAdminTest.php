<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    protected function createFacility(string $tab = 'INDOOR', int $order = 1): Facility
    {
        return Facility::create([
            'slug' => strtolower($tab),
            'tab_name' => $tab,
            'title' => $tab.' Lounge Work Space',
            'label' => $tab.' Space',
            'subtitle' => 'Workspace & Lounge',
            'description' => 'Area nyaman dan tenang untuk bekerja dan berkumpul bersama rekan.',
            'features' => ['Wi-Fi Cepat', 'AC Dingin', 'Stopkontak'],
            'photos' => [
                [
                    'image' => 'sample.jpg',
                    'caption' => 'Foto Utama',
                    'alt' => 'Foto Utama Nucomu',
                ],
            ],
            'sort_order' => $order,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_facility_index_with_redesigned_ui(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.facilities.index'));

        $response->assertStatus(200);

        // Header and Actions
        $response->assertSee('Fasilitas');
        $response->assertSee('Tambah Fasilitas');
        $response->assertSee('? Petunjuk');

        // Help panel
        $response->assertSee('Panduan Mengelola Carousel Fasilitas');
        $response->assertSee('Nama &amp; Tombol', false);
        $response->assertSee('Penjelasan &amp; Poin', false);
        $response->assertSee('Unggah 1&ndash;3 Foto', false);
        $response->assertSee('Urutan Tampil');

        // Verify old legacy terms are removed
        $response->assertDontSee('Modul 3 &bull; Carousel Fasilitas Nucomu', false);
        $response->assertDontSee('Tambah Slide Baru');
        $response->assertDontSee('Lihat di Web');

        // Verify 3 choices modal exists
        $response->assertSee('Hapus Fasilitas?');
        $response->assertSee('Sembunyikan Saja');
        $response->assertSee('Hapus');
    }

    public function test_admin_can_reorder_facilities(): void
    {
        $admin = $this->getAdminUser();
        Facility::query()->delete();
        $f1 = $this->createFacility('INDOOR', 1);
        $f2 = $this->createFacility('OUTDOOR', 2);

        // Move f1 down
        $response = $this->actingAs($admin)->post(route('admin.facilities.reorder'), [
            'facility_id' => $f1->id,
            'direction' => 'down',
        ]);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success', 'Urutan fasilitas berhasil diperbarui.');

        $f1->refresh();
        $f2->refresh();
        $this->assertEquals(2, $f1->sort_order);
        $this->assertEquals(1, $f2->sort_order);
    }

    public function test_admin_can_toggle_facility_active_status(): void
    {
        $admin = $this->getAdminUser();
        $facility = $this->createFacility('INDOOR', 1);

        $this->assertTrue($facility->is_active);

        $response = $this->actingAs($admin)->post(route('admin.facilities.toggle', $facility));

        $response->assertStatus(302);
        $facility->refresh();
        $this->assertFalse($facility->is_active);
    }

    public function test_admin_can_access_create_and_edit_forms_with_proper_ui(): void
    {
        $admin = $this->getAdminUser();
        $facility = $this->createFacility('INDOOR', 1);

        // Create page
        $resCreate = $this->actingAs($admin)->get(route('admin.facilities.create'));
        $resCreate->assertStatus(200);
        $resCreate->assertSee('Nama tombol pilihan');
        $resCreate->assertSee('Judul fasilitas');
        $resCreate->assertSee('Daftar keunggulan fasilitas');
        $resCreate->assertSee('Foto fasilitas');
        $resCreate->assertSee('Contoh tampilan di website');

        // Edit page
        $resEdit = $this->actingAs($admin)->get(route('admin.facilities.edit', $facility));
        $resEdit->assertStatus(200);
        $resEdit->assertSee('Nama tombol pilihan');
        $resEdit->assertSee('Contoh tampilan di website');
        $resEdit->assertSee('Simpan perubahan');
    }
}
