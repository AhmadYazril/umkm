<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numerify('###'),
            'description' => $this->faker->sentence(12),
            'price' => $this->faker->randomElement([15000, 18000, 20000, 22000, 25000, 28000, 30000, 32000, 35000, 38000, 40000]),
            'image' => null,
            'is_available' => $this->faker->boolean(85),
            'is_featured' => false,
            'is_best_seller' => false,
            'is_sample' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
