<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Rina S.',       'content' => 'Tempatnya nyaman banget, cocok buat nugas sambil ngopi! Interior monokromnya estetik banget.', 'rating' => 5],
            ['name' => 'Bagas P.',      'content' => 'Dessert-nya enak, apalagi banoffee-nya, langsung bikin kangen. Rasa otentiknya beda dari yang lain!', 'rating' => 5],
            ['name' => 'Dewi A.',       'content' => 'WiFi kencang, colokan banyak, recommended banget buat WFC! Betah kerja di sini seharian.', 'rating' => 5],
            ['name' => 'Fajar M.',      'content' => 'Harganya terjangkau untuk kafe sekeren ini. Cheesecake-nya creamy, tiramisu-nya top!', 'rating' => 5],
            ['name' => 'Siti N.',       'content' => 'Nucomu Coffee signature-nya unik, ada rasa khas yang bikin beda dari kafe lain di Tulungagung.', 'rating' => 5],
            ['name' => 'Reza K.',       'content' => 'Butterscloud-nya worth it banget, jadi minuman favoritku sekarang! Harus cobain kalau mampir.', 'rating' => 5],
            ['name' => 'Anisa T.',      'content' => 'Suasana tenang dan elegan, cocok buat kerja maupun sekadar bersantai. Pasti balik lagi!', 'rating' => 5],
            ['name' => 'Budi R.',       'content' => 'Pelayanannya ramah, tempatnya bersih dan rapi. Donut Choco Pistachio-nya surga banget!', 'rating' => 4],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['name' => $t['name'], 'content' => $t['content']],
                ['rating' => $t['rating'], 'is_sample' => true, 'is_active' => true]
            );
        }
    }
}
