<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryAdminTest extends TestCase
{
    use RefreshDatabase;

    private function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_view_gallery_index(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('Galeri Foto Kafe');
        $response->assertSee('Unggah Foto Baru');
        $response->assertSee('Panduan Ukuran');
    }

    public function test_gallery_store_requires_caption_and_image(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post(route('admin.gallery.store'), [
            'caption' => '',
            'image' => null,
        ]);

        $response->assertSessionHasErrors(['caption', 'image']);
    }

    public function test_gallery_store_rejects_files_larger_than_2mb(): void
    {
        $admin = $this->getAdminUser();

        // 3 MB fake image
        $largeImage = UploadedFile::fake()->create('huge.jpg', 3072, 'image/jpeg');

        $response = $this->actingAs($admin)->post(route('admin.gallery.store'), [
            'caption' => 'Suasana Bar Nucomu',
            'image' => $largeImage,
        ]);

        $response->assertSessionHasErrors(['image']);
    }

    public function test_admin_can_store_new_gallery_photo(): void
    {
        $admin = $this->getAdminUser();

        $fakeImage = UploadedFile::fake()->image('coffee_bar.jpg', 1200, 900)->size(500);

        $response = $this->actingAs($admin)->post(route('admin.gallery.store'), [
            'caption' => 'Manual Brew V60 Signature',
            'description' => 'Aroma fruity dari biji kopi lokal pilihan.',
            'category' => 'coffee',
            'sort_order' => 10,
            'is_active' => '1',
            'image' => $fakeImage,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $response->assertSessionHas('success');

        $item = GalleryItem::where('caption', 'Manual Brew V60 Signature')->first();
        $this->assertNotNull($item);
        $this->assertSame('coffee', $item->category);
        $this->assertSame(10, $item->sort_order);
        $this->assertTrue($item->is_active);

        // Verify physical file was placed in public/uploads/gallery
        $uploadedPath = public_path('uploads/gallery/'.$item->image);
        $this->assertFileExists($uploadedPath);

        // Cleanup
        $item->deleteImageFile();
        $item->delete();
    }

    public function test_admin_can_update_gallery_photo_and_caption(): void
    {
        $admin = $this->getAdminUser();

        // Create initial item
        $fakeImage1 = UploadedFile::fake()->image('init.jpg', 800, 800)->size(300);
        $this->actingAs($admin)->post(route('admin.gallery.store'), [
            'caption' => 'Foto Awal',
            'image' => $fakeImage1,
        ]);

        $item = GalleryItem::where('caption', 'Foto Awal')->first();
        $this->assertNotNull($item);
        $oldFile = public_path('uploads/gallery/'.$item->image);
        $this->assertFileExists($oldFile);

        // Update with replacement image & new caption
        $fakeImage2 = UploadedFile::fake()->image('updated.webp', 1080, 1080)->size(400);
        $response = $this->actingAs($admin)->put(route('admin.gallery.update', $item->id), [
            'caption' => 'Foto Diperbarui',
            'description' => 'Deskripsi baru yang diperbarui.',
            'category' => 'ambience',
            'sort_order' => 5,
            'is_active' => '1',
            'image' => $fakeImage2,
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertSame('Foto Diperbarui', $item->caption);
        $this->assertSame('Deskripsi baru yang diperbarui.', $item->description);
        $this->assertSame(5, $item->sort_order);

        // Old file must be unlinked
        $this->assertFileDoesNotExist($oldFile);

        // New file exists
        $newFile = public_path('uploads/gallery/'.$item->image);
        $this->assertFileExists($newFile);

        // Cleanup
        $item->deleteImageFile();
        $item->delete();
    }

    public function test_admin_can_toggle_gallery_active_status(): void
    {
        $admin = $this->getAdminUser();

        $item = GalleryItem::create([
            'image' => 'sample.jpg',
            'caption' => 'Toggle Test Item',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Toggle from active (true) to inactive (false)
        $response = $this->actingAs($admin)->patch(route('admin.gallery.toggle-active', $item->id));
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertFalse($item->is_active);

        // Toggle back to active (true) via JSON/AJAX
        $jsonResponse = $this->actingAs($admin)->patchJson(route('admin.gallery.toggle-active', $item->id));
        $jsonResponse->assertJson([
            'success' => true,
            'is_active' => true,
        ]);

        $item->refresh();
        $this->assertTrue($item->is_active);

        $item->delete();
    }

    public function test_admin_can_delete_gallery_item_and_file_is_removed(): void
    {
        $admin = $this->getAdminUser();

        $fakeImage = UploadedFile::fake()->image('to_delete.jpg', 600, 600)->size(200);
        $this->actingAs($admin)->post(route('admin.gallery.store'), [
            'caption' => 'Foto Untuk Dihapus',
            'image' => $fakeImage,
        ]);

        $item = GalleryItem::where('caption', 'Foto Untuk Dihapus')->first();
        $this->assertNotNull($item);
        $filePath = public_path('uploads/gallery/'.$item->image);
        $this->assertFileExists($filePath);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.gallery.destroy', $item->id));
        $response->assertRedirect(route('admin.gallery.index'));
        $response->assertSessionHas('success');

        // Verify database and file
        $this->assertDatabaseMissing('gallery_items', ['id' => $item->id]);
        $this->assertFileDoesNotExist($filePath);
    }

    public function test_public_gallery_page_only_renders_active_items(): void
    {
        GalleryItem::create([
            'image' => 'active_shot.jpg',
            'caption' => 'Foto Publik Terlihat',
            'category' => 'coffee',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        GalleryItem::create([
            'image' => 'hidden_shot.jpg',
            'caption' => 'Foto Rahasia Tersembunyi',
            'category' => 'interior',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $response = $this->get(route('gallery'));
        $response->assertStatus(200);
        $response->assertSee('Foto Publik Terlihat');
        $response->assertDontSee('Foto Rahasia Tersembunyi');
    }
}
