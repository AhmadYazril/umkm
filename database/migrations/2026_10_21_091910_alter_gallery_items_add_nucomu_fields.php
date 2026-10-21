<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add Nucomu story-feed fields to gallery_items table.
     */
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('author')->nullable()->after('caption');
            $table->string('time_label')->nullable()->after('author');
            $table->string('category_badge')->nullable()->after('time_label');
            $table->string('avatar_url')->nullable()->after('category_badge');
            $table->text('description')->nullable()->after('avatar_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['author', 'time_label', 'category_badge', 'avatar_url', 'description']);
        });
    }
};
