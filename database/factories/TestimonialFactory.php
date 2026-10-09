<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = [
            'Tempatnya nyaman banget, cocok buat nugas sambil ngopi!',
            'Dessert-nya enak, apalagi banoffee-nya, langsung bikin kangen.',
            'Konsep monokrom-nya keren, foto-fotonya jadi aesthetic banget.',
            'WiFi kencang, colokan banyak, recommended banget buat WFC!',
            'Harganya terjangkau, porsi pas, rasa mantap. Balik lagi!',
            'Suasana tenang dan elegan, cocok buat kerja sambil minum kopi.',
            'Cheesecake-nya creamy banget, tiramisu-nya juga top!',
            'Pelayanannya ramah, tempatnya bersih dan rapi.',
            'Nucomu Coffee-nya unik, ada rasa khas yang bikin beda dari kafe lain.',
            'Butterscloud-nya worth it banget, minuman favoritku sekarang!',
        ];

        return [
            'name' => $this->faker->name(),
            'content' => $this->faker->randomElement($reviews),
            'rating' => $this->faker->numberBetween(4, 5),
            'is_sample' => true,
            'is_active' => true,
        ];
    }
}
