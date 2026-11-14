<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryPhotoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Track uploaded filenames so test files are always cleaned up.
     *
     * @var array<int, string>
     */
    private array $createdUploadFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->createdUploadFiles as $filename) {
            $path = public_path('uploads/categories/'.$filename);
            if (is_file($path)) {
                @unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_admin_can_upload_and_replace_category_photo(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $storeResponse = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), [
                'name' => 'Matcha Series',
                'sort_order' => 1,
                'is_active' => 1,
                'image' => UploadedFile::fake()->image('matcha.jpg', 400, 400),
            ]);

        $storeResponse->assertRedirect(route('admin.categories.index'));
        $storeResponse->assertSessionHasNoErrors();

        $category = Category::where('slug', 'matcha-series')->firstOrFail();
        $firstImage = (string) $category->image;
        $this->assertNotSame('', $firstImage);
        $this->createdUploadFiles[] = $firstImage;

        $this->assertFileExists(public_path('uploads/categories/'.$firstImage));
        $this->assertStringContainsString('uploads/categories/'.$firstImage, (string) $category->image_url);

        $updateResponse = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $category), [
                'name' => 'Matcha Signature',
                'sort_order' => 2,
                'is_active' => 1,
                'image' => UploadedFile::fake()->image('matcha-v2.png', 400, 400),
            ]);

        $updateResponse->assertRedirect(route('admin.categories.index'));
        $updateResponse->assertSessionHasNoErrors();

        $category->refresh();
        $secondImage = (string) $category->image;
        $this->assertNotSame('', $secondImage);
        $this->assertNotSame($firstImage, $secondImage);
        $this->createdUploadFiles[] = $secondImage;

        $this->assertFileDoesNotExist(public_path('uploads/categories/'.$firstImage));
        $this->assertFileExists(public_path('uploads/categories/'.$secondImage));
    }

    public function test_admin_can_remove_existing_category_photo_on_update(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Pastry Corner',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('pastry.jpg', 400, 400),
        ])->assertSessionHasNoErrors();

        $category = Category::where('slug', 'pastry-corner')->firstOrFail();
        $oldImage = (string) $category->image;
        $this->createdUploadFiles[] = $oldImage;
        $this->assertFileExists(public_path('uploads/categories/'.$oldImage));

        $response = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $category), [
                'name' => 'Pastry Corner',
                'sort_order' => 1,
                'is_active' => 1,
                'remove_image' => 1,
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHasNoErrors();

        $category->refresh();
        $this->assertNull($category->image);
        $this->assertNull($category->image_url);
        $this->assertFileDoesNotExist(public_path('uploads/categories/'.$oldImage));
    }

    public function test_deleting_empty_category_removes_uploaded_photo_file(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Seasonal Drinks',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('seasonal.webp', 400, 400),
        ])->assertSessionHasNoErrors();

        $category = Category::where('slug', 'seasonal-drinks')->firstOrFail();
        $uploadedImage = (string) $category->image;
        $this->createdUploadFiles[] = $uploadedImage;
        $this->assertFileExists(public_path('uploads/categories/'.$uploadedImage));

        $response = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertFileDoesNotExist(public_path('uploads/categories/'.$uploadedImage));
    }

    public function test_rejects_non_image_upload_for_category_photo(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), [
                'name' => 'Invalid File Category',
                'sort_order' => 1,
                'is_active' => 1,
                'image' => UploadedFile::fake()->create('manual.pdf', 120, 'application/pdf'),
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('categories', ['name' => 'Invalid File Category']);
    }

    public function test_inline_json_category_creation_succeeds_without_photo(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('admin.categories.store'), [
            'name' => 'Seasonal Brew',
        ]);

        $response->assertSuccessful();
        $response->assertJsonPath('category.name', 'Seasonal Brew');
        $response->assertJsonPath('category.image', null);

        $this->assertDatabaseHas('categories', [
            'name' => 'Seasonal Brew',
            'slug' => 'seasonal-brew',
            'image' => null,
        ]);
    }

    public function test_homepage_renders_active_categories_with_uploaded_photo_and_fallback(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Dessert Box',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('dessert-box.jpg', 400, 400),
        ])->assertSessionHasNoErrors();

        $withPhoto = Category::where('slug', 'dessert-box')->firstOrFail();
        $this->createdUploadFiles[] = (string) $withPhoto->image;

        Category::create([
            'name' => 'Kopi',
            'slug' => 'kopi',
            'image' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Hidden Archive',
            'slug' => 'hidden-archive',
            'image' => null,
            'sort_order' => 3,
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('OUR MENU CATEGORIES');
        $response->assertSee('Dessert Box');
        $response->assertSee('uploads/categories/'.$withPhoto->image);
        $response->assertSee('Kopi');
        $response->assertDontSee('Hidden Archive');
    }

    public function test_admin_menus_page_renders_category_photo_upload_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Signature Tea',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('tea.jpg', 400, 400),
        ])->assertSessionHasNoErrors();

        $category = Category::where('slug', 'signature-tea')->firstOrFail();
        $this->createdUploadFiles[] = (string) $category->image;

        $response = $this->actingAs($admin)->get(route('admin.menus.index'));

        $response->assertOk();
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('id="drawer_add_cat_image"', false);
        $response->assertSee('id="drawer_edit_cat_image"', false);
        $response->assertSee('id="drawer_edit_cat_remove_image"', false);
        $response->assertSee('uploads/categories/'.$category->image);
    }

    public function test_quick_edit_from_menus_drawer_updates_photo_without_clearing_metadata(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $category = Category::create([
            'name' => 'Mocktail',
            'slug' => 'mocktail',
            'sort_order' => 4,
            'is_active' => true,
            'description' => 'Sajian segar non-kopi',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.menus.index'))
            ->put(route('admin.categories.update', $category), [
                'name' => 'Artisan Mocktail',
                'image' => UploadedFile::fake()->image('mocktail.png', 400, 400),
            ]);

        $response->assertRedirect(route('admin.menus.index'));
        $response->assertSessionHasNoErrors();

        $category->refresh();
        $this->createdUploadFiles[] = (string) $category->image;

        $this->assertSame('Artisan Mocktail', $category->name);
        $this->assertNotNull($category->image);
        $this->assertFileExists(public_path('uploads/categories/'.$category->image));
        $this->assertSame(4, $category->sort_order);
        $this->assertTrue($category->is_active);
        $this->assertSame('Sajian segar non-kopi', $category->description);
    }
}
