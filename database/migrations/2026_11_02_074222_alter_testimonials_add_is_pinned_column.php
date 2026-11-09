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
        if (Schema::hasTable('testimonials') && ! Schema::hasColumn('testimonials', 'is_pinned')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            });

            // Initial pin for approved sample testimonials (IDs: 1, 2, 3, 5)
            DB::table('testimonials')
                ->whereIn('id', [1, 2, 3, 5])
                ->update(['is_pinned' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('testimonials') && Schema::hasColumn('testimonials', 'is_pinned')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropColumn('is_pinned');
            });
        }
    }
};
