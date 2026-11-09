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
        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'layout_mode')) {
                $table->string('layout_mode', 30)->default('poster')->after('media_path');
            }

            // Hapus kolom-kolom terkait tampilan HP jika sempat ada di environment lain
            if (Schema::hasColumn('banners', 'mobile_media_path')) {
                $table->dropColumn('mobile_media_path');
            }
            if (Schema::hasColumn('banners', 'mobile_image')) {
                $table->dropColumn('mobile_image');
            }
            if (Schema::hasColumn('banners', 'focus_position')) {
                $table->dropColumn('focus_position');
            }
        });

        // Banner lama yang sudah ada di database tetap aman sebagai mode 'split' (Terbagi)
        DB::table('banners')
            ->whereNull('layout_mode')
            ->orWhere('layout_mode', '')
            ->update(['layout_mode' => 'split']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'layout_mode')) {
                $table->dropColumn('layout_mode');
            }
        });
    }
};
