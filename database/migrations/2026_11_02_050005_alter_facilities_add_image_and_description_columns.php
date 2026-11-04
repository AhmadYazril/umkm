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
            $table->string('image')->nullable()->after('label');
            $table->text('description')->nullable()->after('image');
        });

        // Seed default photo and description only if columns are currently empty
        $defaults = [
            'Banyak Colokan' => [
                'desc' => 'Stopkontak melimpah di setiap area meja untuk menunjang daya laptop dan gadget selama Anda beraktivitas.',
                'image' => 'images/nucomu/nucomu_story_2.png',
            ],
            'WiFi Kencang' => [
                'desc' => 'Akses internet berkecepatan tinggi yang stabil untuk kebutuhan WFC, streaming, tugas kuliah, maupun meeting daring.',
                'image' => 'images/nucomu/nucomu_story_4.png',
            ],
            'Toilet Bersih' => [
                'desc' => 'Fasilitas toilet yang selalu terawat, bersih, wangi, dan higienis demi kenyamanan seluruh pengunjung cafe.',
                'image' => 'images/nucomu/nucomu_story_3.png',
            ],
            'Mushola' => [
                'desc' => 'Mushola bersih dan tenang lengkap dengan sarana wudhu terpisah, sajadah, mukena, dan pendingin ruangan AC.',
                'image' => 'images/nucomu/nucomu_story_1.png',
            ],
            'Cocok untuk WFC' => [
                'desc' => 'Suasana ruang kerja kondusif dengan meja ergonomis dan pencahayaan hangat yang nyaman untuk bekerja berjam-jam.',
                'image' => 'images/nucomu/nucomu_story_2.png',
            ],
            'Minuman Variatif' => [
                'desc' => 'Pilihan racikan kopi artisanal Sendang, minuman non-kopi segar, dan dessert berkualitas untuk menemani hari Anda.',
                'image' => 'images/nucomu-latte-hero.jpg',
            ],
            'Area Parkir' => [
                'desc' => 'Area parkir kendaraan roda dua dan roda empat yang lega, teratur, dan aman di lingkungan cafe.',
                'image' => 'images/nucomu/nucomu_outdoor_2.png',
            ],
        ];

        try {
            $facilities = DB::table('facilities')->get();
            foreach ($facilities as $facility) {
                $update = [];
                if (empty($facility->description) && isset($defaults[$facility->label])) {
                    $update['description'] = $defaults[$facility->label]['desc'];
                }
                if (empty($facility->image) && isset($defaults[$facility->label])) {
                    $update['image'] = $defaults[$facility->label]['image'];
                }
                if (! empty($update)) {
                    DB::table('facilities')->where('id', $facility->id)->update($update);
                }
            }
        } catch (Throwable $e) {
            // Safe fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['image', 'description']);
        });
    }
};
