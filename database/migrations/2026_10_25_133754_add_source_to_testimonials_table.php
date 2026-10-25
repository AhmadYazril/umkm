<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (! Schema::hasColumn('testimonials', 'source')) {
                $table->string('source')->default('website')->after('rating');
            }
            if (! Schema::hasColumn('testimonials', 'review_image')) {
                $table->string('review_image')->nullable()->after('avatar_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'review_image')) {
                $table->dropColumn('review_image');
            }
            if (Schema::hasColumn('testimonials', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
};
