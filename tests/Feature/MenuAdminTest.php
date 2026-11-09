<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MenuAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    protected function createCategory(string $name = 'Signature Coffee', int $order = 1): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'sort_order' => $order,
            'is_active' => true,
        ]);
    }

    protected function createMenu(Category $category, array $attributes = []): Menu
    {
        return Menu::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Caramel Macchiato',
            'slug' => 'caramel-macchiato-'.uniqid(),
            'description' => 'Espresso dengan sirup vanila dan saus karamel lezat.',
            'price' => 28000,
            'image' => null,
            'is_available' => true,
            'is_featured' => false,
            'is_best_seller' => false,
            'sort_order' => 1,
        ], $attributes));
    }

    public function test_admin_can_access_menu_index_with_redesigned_ui(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $this->createMenu($cat, ['name' => 'Espresso Classic', 'price' => 20000]);

        $response = $this->actingAs($admin)->get(route('admin.menus.index'));

        $response->assertStatus(200);

        // Header and Actions
        $response->assertSee('Menu');
        $response->assertSee('Tambah Menu');
        $response->assertSee('Kelola Kategori');
        $response->assertSee('? Petunjuk');

        // Help panel content
        $response->assertSee('Panduan Mengelola Menu &amp; Kategori', false);
        $response->assertSee('Nama &amp; Kategori', false);
        $response->assertSee('Harga &amp; Penjelasan', false);
        $response->assertSee('Foto Sajian 1:1', false);
        $response->assertSee('Menu Andalan', false);

        // Clean Summary row
        $response->assertSee('menu terdaftar');
        $response->assertDontSee('tersedia');
        $response->assertDontSee('habis');

        // Toolbar: category chips, status, search, and view switcher
        $response->assertSee('Semua (1)');
        $response->assertSee('Kopi (1)');
        $response->assertDontSee('Tersedia (1)');
        $response->assertDontSee('Habis (0)');
        $response->assertSee('Terarsip (0)');
        $response->assertSee('Cari nama menu...');
        $response->assertSee('Kartu');
        $response->assertSee('Daftar');

        // Menu item
        $response->assertSee('Espresso Classic');
        $response->assertSee('Rp 20.000');
    }

    public function test_sample_notice_bar_and_badge_are_not_shown(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $this->createMenu($cat, ['name' => 'Sample Latte', 'is_sample' => true]);

        $response = $this->actingAs($admin)->get(route('admin.menus.index'));

        $response->assertStatus(200);
        $response->assertDontSee('menu masih berupa contoh bawaan sistem');
        $response->assertDontSee('Hapus semua contoh');
    }

    public function test_admin_can_access_create_menu_form(): void
    {
        $admin = $this->getAdminUser();
        $this->createCategory('Mocktail');

        $response = $this->actingAs($admin)->get(route('admin.menus.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Menu Baru');
        $response->assertSee('1. Nama dan kategori');
        $response->assertSee('2. Harga dan penjelasan');
        $response->assertSee('3. Foto dan status');
        $response->assertSee('Pratinjau kartu menu di website');
        $response->assertSee('Simpan &amp; Tambah Lagi', false);
        $response->assertSee('Simpan Menu');
        $response->assertSee('Batalkan');
    }

    public function test_admin_can_store_menu(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Dessert');

        $response = $this->actingAs($admin)->post(route('admin.menus.store'), [
            'category_id' => $cat->id,
            'name' => 'Matcha Mille Crepe',
            'price' => 35000,
            'description' => 'Kue lapis lembut dengan bubuk matcha murni Jepang.',
            'is_available' => 1,
            'is_featured' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseHas('menus', [
            'name' => 'Matcha Mille Crepe',
            'price' => 35000,
            'is_available' => true,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_store_and_add_another_menu(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Dessert');

        $response = $this->actingAs($admin)->post(route('admin.menus.store'), [
            'action' => 'save_and_add_another',
            'category_id' => $cat->id,
            'name' => 'Croissant Almond',
            'price' => 25000,
            'description' => 'Pastry renyah bermentega dengan taburan almond panggang.',
            'is_available' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.create'));
        $this->assertDatabaseHas('menus', [
            'name' => 'Croissant Almond',
            'price' => 25000,
        ]);
    }

    public function test_admin_can_access_edit_menu_form(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, ['name' => 'Cappuccino Brulee']);

        $response = $this->actingAs($admin)->get(route('admin.menus.edit', $menu->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Menu: Cappuccino Brulee');
        $response->assertSee('Simpan Perubahan');
        $response->assertSee('Cappuccino Brulee');
    }

    public function test_admin_can_update_menu(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, [
            'name' => 'Original Coffee',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.menus.update', $menu->id), [
            'category_id' => $cat->id,
            'name' => 'Real Coffee Nucomu',
            'price' => 28000,
            'description' => 'Kopi asli pilihan barista.',
            'is_available' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.index'));

        $menu->refresh();
        $this->assertEquals('Real Coffee Nucomu', $menu->name);
        $this->assertEquals(28000, $menu->price);
        $this->assertTrue($menu->is_available);
    }

    public function test_admin_can_archive_view_and_restore_menu(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, ['name' => 'Menu To Archive']);

        // Arsipkan menu (soft delete)
        $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu->id), [
            'action' => 'archive',
        ]);
        $this->assertSoftDeleted('menus', ['id' => $menu->id]);

        // Cek halaman status=trashed
        $responseTrashed = $this->actingAs($admin)->get(route('admin.menus.index', ['status' => 'trashed']));
        $responseTrashed->assertStatus(200);
        $responseTrashed->assertSee('Menu To Archive');
        $responseTrashed->assertSee('Pulihkan');

        // Pulihkan menu
        $responseRestore = $this->actingAs($admin)->post(route('admin.menus.restore', $menu->id));
        $responseRestore->assertRedirect();

        $menu->refresh();
        $this->assertFalse($menu->trashed());
    }

    public function test_admin_can_toggle_featured_ajax(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, ['is_featured' => false]);

        $response = $this->actingAs($admin)->postJson(route('admin.menus.toggleFeatured', $menu->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_featured' => true]);

        $menu->refresh();
        $this->assertTrue($menu->is_featured);
    }

    public function test_delete_menu_with_orders_prevents_force_delete_and_archives_safely(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, ['name' => 'Ordered Latte']);

        // Buat order dan order item
        $order = Order::create([
            'code' => 'ORD-TEST-001',
            'customer_name' => 'Budi',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'payment_status' => 'paid',
            'status' => 'completed',
            'subtotal' => 28000,
            'total' => 28000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'unit_price' => 28000,
            'qty' => 1,
            'line_total' => 28000,
        ]);

        // 1. Coba force delete via json -> harus ditolak 422
        $responseForce = $this->actingAs($admin)->deleteJson(route('admin.menus.destroy', $menu->id), [
            'force' => 1,
        ]);
        $responseForce->assertStatus(422);
        $responseForce->assertJson(['success' => false]);

        // Menu masih ada
        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'deleted_at' => null]);

        // 2. Arsipkan / soft delete aman
        $responseSafe = $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu->id));
        $responseSafe->assertRedirect();

        // Menu berstatus soft deleted, order item tetap aman
        $this->assertSoftDeleted('menus', ['id' => $menu->id]);
        $this->assertDatabaseHas('order_items', ['menu_id' => $menu->id]);
    }

    public function test_delete_menu_without_orders_can_be_force_deleted(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Kopi');
        $menu = $this->createMenu($cat, ['name' => 'Unordered Drink']);

        $response = $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu->id), [
            'force' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    public function test_category_cannot_be_deleted_if_it_contains_menus(): void
    {
        $admin = $this->getAdminUser();
        $cat = $this->createCategory('Non-Kopi');
        $this->createMenu($cat, ['name' => 'Earl Grey Milk Tea']);

        // Coba hapus via json
        $response = $this->actingAs($admin)->deleteJson(route('admin.categories.destroy', $cat->id));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);

        // Kategori tidak terhapus
        $this->assertDatabaseHas('categories', ['id' => $cat->id]);
    }

    public function test_category_can_be_reordered(): void
    {
        $admin = $this->getAdminUser();
        $cat1 = $this->createCategory('Cat 1', 1);
        $cat2 = $this->createCategory('Cat 2', 2);

        $response = $this->actingAs($admin)->postJson(route('admin.categories.reorder'), [
            'order' => [$cat2->id, $cat1->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $cat1->refresh();
        $cat2->refresh();

        $this->assertEquals(1, $cat2->sort_order);
        $this->assertEquals(2, $cat1->sort_order);
    }
}
