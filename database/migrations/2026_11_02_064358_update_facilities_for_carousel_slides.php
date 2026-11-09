<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (! Schema::hasColumn('facilities', 'slug')) {
                $table->string('slug', 100)->nullable()->after('id');
            }
            if (! Schema::hasColumn('facilities', 'tab_name')) {
                $table->string('tab_name', 100)->nullable()->after('slug');
            }
            if (! Schema::hasColumn('facilities', 'title')) {
                $table->string('title', 255)->nullable()->after('tab_name');
            }
            if (! Schema::hasColumn('facilities', 'badge')) {
                $table->string('badge', 100)->nullable()->after('title');
            }
            if (! Schema::hasColumn('facilities', 'subtitle')) {
                $table->string('subtitle', 255)->nullable()->after('badge');
            }
            if (! Schema::hasColumn('facilities', 'features')) {
                $table->json('features')->nullable()->after('description');
            }
            if (! Schema::hasColumn('facilities', 'photos')) {
                $table->json('photos')->nullable()->after('features');
            }
        });

        // 1. Hapus data Restroom / Toilet Bersih
        DB::table('facilities')
            ->where('icon', 'bath')
            ->orWhere('label', 'like', '%Toilet%')
            ->orWhere('label', 'like', '%Restroom%')
            ->delete();

        // 2. Bersihkan baris-baris kartu lama selain 3 slide utama (Indoor, Outdoor, Musholla)
        DB::table('facilities')
            ->whereIn('label', ['Banyak Colokan', 'WiFi Kencang', 'Cocok untuk WFC', 'Minuman Variatif', 'Area Parkir'])
            ->delete();

        // 3. Siapkan 3 slide awal: Indoor, Outdoor, Musholla
        $initialSlides = [
            [
                'slug' => 'indoor',
                'tab_name' => 'INDOOR',
                'title' => 'Indoor Lounge Work Space',
                'label' => 'Indoor Lounge Work Space',
                'badge' => '01 / WORKSPACE & LOUNGE',
                'subtitle' => 'Workspace & Lounge',
                'description' => 'Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC (Work From Cafe) maupun rapat santai.',
                'features' => json_encode([
                    'Suhu ruangan sejuk & sirkulasi udara bersih',
                    'Pencahayaan hangat bebas silau untuk kenyamanan mata',
                    'Stopkontak di setiap meja & Wi-Fi berkecepatan tinggi',
                ]),
                'photos' => json_encode([
                    [
                        'image' => 'images/nucomu/nucomu_story_2.png',
                        'caption' => 'Meja Ergonomis & Suasana Asri',
                        'alt' => 'Indoor Lounge Nucomu Cafe — Meja Ergonomis & Monstera',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_story_1.png',
                        'caption' => 'Espresso Bar & Counter Seduh',
                        'alt' => 'Espresso Bar Nucomu Cafe — Counter Seduh & Mesin Kopi',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_story_4.png',
                        'caption' => 'Sudut Apparel & Cermin Estetis',
                        'alt' => 'Sudut Apparel Nucomu Cafe — Merchandise & Cermin Estetis',
                    ],
                ]),
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'outdoor',
                'tab_name' => 'OUTDOOR',
                'title' => 'Outdoor Garden Terrace',
                'label' => 'Outdoor Garden Terrace',
                'badge' => '02 / OUTDOOR TERRACE',
                'subtitle' => 'Garden Terrace',
                'description' => 'Taman terbuka bersuasana asri dengan naungan pepohonan rindang dan pencahayaan malam yang temaram romantis. Tempat favorit untuk menikmati senja, angin sepoi-sepoi, dan obrolan santai tanpa sekat kaku.',
                'features' => json_encode([
                    'Smoking area yang lega dan asri',
                    'Suasana hijau teduh dengan bangku taman nyaman',
                    'Atmosfer santai untuk bercengkerama sore hingga malam',
                ]),
                'photos' => json_encode([
                    [
                        'image' => 'images/nucomu/nucomu_story_3.png',
                        'caption' => 'Bangku Kayu & Lampu Temaram',
                        'alt' => 'Outdoor Garden Terrace Nucomu Cafe — Kursi Kayu Alami & Lampu Hangat',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_outdoor_2.png',
                        'caption' => 'Area Terbuka Hijau & Sejuk',
                        'alt' => 'Outdoor Garden Nucomu Cafe — Area Terbuka Hijau & Sejuk',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_outdoor_3.png',
                        'caption' => 'Sudut Santai Sore Hari',
                        'alt' => 'Outdoor Nucomu Cafe — Sudut Santai Sore Hari',
                    ],
                ]),
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'musholla',
                'tab_name' => 'MUSHOLLA',
                'title' => 'Musala Bersih & Terpisah',
                'label' => 'Musala Bersih & Terpisah',
                'badge' => '03 / KENYAMANAN IBADAH',
                'subtitle' => 'Kenyamanan Ibadah',
                'description' => 'Fasilitas musala khusus lengkap dengan sarana wudhu yang selalu terjaga kebersihannya, sajadah, mukena wangi, serta pendingin udara untuk kenyamanan dan ketenangan ibadah Anda.',
                'features' => json_encode([
                    'Sarana Wudhu Bersih',
                    'Mukena & Sajadah',
                    'Sejuk & Tenang',
                ]),
                'photos' => json_encode([
                    [
                        'image' => 'images/nucomu/nucomu_story_1.png',
                        'caption' => 'Area Ibadah Bersih & Tenang',
                        'alt' => 'Mushola Nucomu Cafe — Bersih & Tenang',
                    ],
                ]),
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($initialSlides as $slide) {
            $existing = DB::table('facilities')->where('slug', $slide['slug'])->first();
            if (! $existing && $slide['slug'] === 'musholla') {
                $existing = DB::table('facilities')->where('label', 'like', '%Mushola%')->first();
            }

            if ($existing) {
                // Update hanya jika kolom carousel masih kosong agar perubahan admin tidak ditimpa
                $update = [];
                if (empty($existing->slug)) {
                    $update['slug'] = $slide['slug'];
                }
                if (empty($existing->tab_name)) {
                    $update['tab_name'] = $slide['tab_name'];
                }
                if (empty($existing->title)) {
                    $update['title'] = $slide['title'];
                }
                if (empty($existing->badge)) {
                    $update['badge'] = $slide['badge'];
                }
                if (empty($existing->subtitle)) {
                    $update['subtitle'] = $slide['subtitle'];
                }
                if (empty($existing->features)) {
                    $update['features'] = $slide['features'];
                }
                if (empty($existing->photos)) {
                    $update['photos'] = $slide['photos'];
                }
                $update['sort_order'] = $slide['sort_order'];
                $update['is_active'] = true;

                DB::table('facilities')->where('id', $existing->id)->update($update);
            } else {
                DB::table('facilities')->insert($slide);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['slug', 'tab_name', 'title', 'badge', 'subtitle', 'features', 'photos']);
        });
    }
};
