<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data contoh lama agar ulasan asli Google Maps & pelanggan web tampil konsisten
        Testimonial::where('is_sample', true)->delete();
    }
}
