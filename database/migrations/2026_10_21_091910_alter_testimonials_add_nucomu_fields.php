<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add Nucomu review fields to testimonials table.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('handle')->nullable()->after('name');
            $table->string('order_tag')->nullable()->after('handle');
            $table->string('avatar_url')->nullable()->after('order_tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['handle', 'order_tag', 'avatar_url']);
        });
    }
};
