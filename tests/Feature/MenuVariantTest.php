<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MenuVariantTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    private array $uploadedTestFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->uploadedTestFiles as $filePath) {
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }

        parent::tearDown();
    }

    public function test_admin_can_create_menu_with_variants_and_addons(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.menus.store'), [
            'category_id' => $category->id,
            'name' => 'Butterscotch Sea Salt Latte',
            'description' => 'Espresso dengan krim gurih sea salt.',
            'price' => 29000,
            'stock' => 20,
            'sync_options' => 1,
            'options' => [
                ['name' => 'Hot', 'type' => 'variant', 'price' => 0],
                ['name' => 'Iced', 'type' => 'variant', 'price' => 0],
                ['name' => 'Extra Shot', 'type' => 'addon', 'price' => 5000],
                ['name' => '   ', 'type' => 'variant', 'price' => 0],
            ],
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $response->assertSessionHasNoErrors();

        $menu = Menu::where('name', 'Butterscotch Sea Salt Latte')->firstOrFail();
        $this->assertCount(3, $menu->options);

        $this->assertDatabaseHas('menu_options', [
            'menu_id' => $menu->id,
            'name' => 'Hot',
            'type' => 'variant',
            'price' => 0,
        ]);
        $this->assertDatabaseHas('menu_options', [
            'menu_id' => $menu->id,
            'name' => 'Extra Shot',
            'type' => 'addon',
            'price' => 5000,
        ]);
    }

    public function test_admin_edit_page_and_index_display_existing_menu_variants(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Signature Aren',
            'slug' => 'signature-aren',
            'price' => 24000,
            'stock' => 15,
            'is_available' => true,
        ]);

        MenuOption::create(['menu_id' => $menu->id, 'name' => 'Regular', 'type' => 'variant', 'price' => 0, 'is_sample' => false]);
        MenuOption::create(['menu_id' => $menu->id, 'name' => 'Large', 'type' => 'variant', 'price' => 5000, 'is_sample' => false]);

        $indexResponse = $this->actingAs($admin)->get(route('admin.menus.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('2 Varian');
        $indexResponse->assertSee('#menu-variants-section');

        $editResponse = $this->actingAs($admin)->get(route('admin.menus.edit', $menu));
        $editResponse->assertOk();
        $editResponse->assertSee('id="menu-variants-section"', false);
        $editResponse->assertSee('name="sync_options"', false);
        $editResponse->assertSee('Regular');
        $editResponse->assertSee('Large');
    }

    public function test_admin_can_replace_and_clear_menu_variants(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Non-Kopi',
            'slug' => 'non-kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Matcha Uji Latte',
            'slug' => 'matcha-uji-latte',
            'price' => 32000,
            'stock' => 12,
            'is_available' => true,
        ]);

        MenuOption::create(['menu_id' => $menu->id, 'name' => 'Hot', 'type' => 'variant', 'price' => 0, 'is_sample' => false]);

        $replaceResponse = $this->actingAs($admin)->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'name' => 'Matcha Uji Latte',
            'price' => 32000,
            'stock' => 12,
            'sync_options' => 1,
            'options' => [
                ['name' => 'Less Ice', 'type' => 'variant', 'price' => 0],
                ['name' => 'Oat Milk', 'type' => 'addon', 'price' => 7000],
            ],
        ]);

        $replaceResponse->assertRedirect(route('admin.menus.index'));
        $replaceResponse->assertSessionHasNoErrors();

        $menu->refresh();
        $this->assertDatabaseMissing('menu_options', ['menu_id' => $menu->id, 'name' => 'Hot']);
        $this->assertDatabaseHas('menu_options', ['menu_id' => $menu->id, 'name' => 'Less Ice', 'type' => 'variant']);
        $this->assertDatabaseHas('menu_options', ['menu_id' => $menu->id, 'name' => 'Oat Milk', 'type' => 'addon', 'price' => 7000]);

        $clearResponse = $this->actingAs($admin)->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'name' => 'Matcha Uji Latte',
            'price' => 32000,
            'stock' => 12,
            'sync_options' => 1,
        ]);

        $clearResponse->assertRedirect(route('admin.menus.index'));
        $this->assertSame(0, $menu->options()->count());
    }

    public function test_partial_menu_update_without_sync_flag_preserves_existing_variants(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Americano',
            'slug' => 'americano',
            'price' => 20000,
            'stock' => 15,
            'is_available' => true,
        ]);

        MenuOption::create(['menu_id' => $menu->id, 'name' => 'Hot', 'type' => 'variant', 'price' => 0, 'is_sample' => false]);

        $response = $this->actingAs($admin)->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'name' => 'Americano Reserve',
            'price' => 22000,
            'stock' => 15,
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseHas('menu_options', [
            'menu_id' => $menu->id,
            'name' => 'Hot',
        ]);
    }

    public function test_rejects_variant_with_extra_price_when_name_is_empty(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.menus.create'))
            ->post(route('admin.menus.store'), [
                'category_id' => $category->id,
                'name' => 'Caramel Latte',
                'price' => 28000,
                'sync_options' => 1,
                'options' => [
                    ['name' => '', 'type' => 'addon', 'price' => 5000],
                ],
            ]);

        $response->assertRedirect(route('admin.menus.create'));
        $response->assertSessionHasErrors('options.0.name');
        $this->assertDatabaseMissing('menus', ['name' => 'Caramel Latte']);
    }

    public function test_public_menu_pages_render_variants_saved_from_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(route('admin.menus.store'), [
            'category_id' => $category->id,
            'name' => 'Cold Brew Reserve',
            'description' => 'Slow-steeped 16 jam.',
            'price' => 29000,
            'stock' => 10,
            'sync_options' => 1,
            'options' => [
                ['name' => 'Single Origin Gayo', 'type' => 'variant', 'price' => 0],
                ['name' => 'Bottle 250ml', 'type' => 'variant', 'price' => 15000],
            ],
        ])->assertSessionHasNoErrors();

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('Single Origin Gayo');
        $homeResponse->assertSee('Bottle 250ml');

        $allMenuResponse = $this->get(route('menu.all'));
        $allMenuResponse->assertOk();
        $allMenuResponse->assertSee('Single Origin Gayo');
        $allMenuResponse->assertSee('Bottle 250ml');
    }

    public function test_homepage_reflects_menu_edited_in_admin_including_name_price_photo_and_variants(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Dessert',
            'slug' => 'dessert',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Banoffee',
            'slug' => 'banoffee',
            'description' => 'Menu lama sebelum diedit.',
            'price' => 30000,
            'stock' => 10,
            'is_available' => true,
            'is_featured' => true,
            'is_best_seller' => true,
            'sort_order' => 1,
        ]);

        $updateResponse = $this->actingAs($admin)->put(route('admin.menus.update', $menu), [
            'category_id' => $category->id,
            'name' => 'Cake Brownies',
            'description' => 'Brownies panggang lembut dengan tiga pilihan rasa.',
            'price' => 50000,
            'stock' => 25,
            'is_available' => 1,
            'is_featured' => 1,
            'image' => UploadedFile::fake()->image('cake-brownies.jpg', 400, 400),
            'sync_options' => 1,
            'options' => [
                ['name' => 'coklat', 'type' => 'variant', 'price' => 0],
                ['name' => 'stroberi', 'type' => 'variant', 'price' => 0],
                ['name' => 'vanila', 'type' => 'variant', 'price' => 0],
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.menus.index'));
        $updateResponse->assertSessionHasNoErrors();

        $menu->refresh();
        $this->assertNotNull($menu->image);
        $this->uploadedTestFiles[] = public_path('uploads/menus/'.$menu->image);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Cake Brownies');
        $homeResponse->assertSee('Cake<br>Brownies', false);
        $homeResponse->assertSee('Rp 50.000');
        $homeResponse->assertSee($menu->image);
        $homeResponse->assertSee('Pilihan Varian');
        $homeResponse->assertSee('coklat');
        $homeResponse->assertSee('stroberi');
        $homeResponse->assertSee('vanila');
        $homeResponse->assertSee('openMenuPhotoPreview(this)', false);
        $homeResponse->assertSee('openCurrentBestMenuPhoto()', false);
        $homeResponse->assertSee('aria-label="Lihat foto Cake Brownies"', false);
        $homeResponse->assertSee('title="Klik untuk melihat foto"', false);
        $homeResponse->assertDontSee('menu-card-avatar-zoom');
        $homeResponse->assertDontSee('zoom_in');
        $homeResponse->assertSee('id="lightbox-modal"', false);
        $homeResponse->assertSee('id="lightbox-caption"', false);
        $homeResponse->assertDontSee('Banoffee');
        $homeResponse->assertDontSee('Nucomu Signature Latte');

        $catalogResponse = $this->get(route('menu.all'));
        $catalogResponse->assertOk();
        $catalogResponse->assertSee('openMenuPhotoPreview(this)', false);
        $catalogResponse->assertSee('aria-label="Lihat foto Cake Brownies"', false);
        $catalogResponse->assertSee('title="Klik untuk melihat foto"', false);
        $catalogResponse->assertDontSee('menu-card-avatar-zoom');
        $catalogResponse->assertDontSee('zoom_in');
        $catalogResponse->assertSee('id="lightbox-modal"', false);
    }
}
