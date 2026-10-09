<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Alter existing categories table to add columns needed by Nucomu Cafe.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('description');
            }
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }
        });

        // Set unique on slug (only if column was just added)
        if (Schema::hasColumn('categories', 'slug')) {
            try {
                \DB::statement('ALTER TABLE categories ADD UNIQUE INDEX categories_slug_unique (slug)');
            } catch (\Exception $e) {
                // May already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            try { $table->dropUnique('categories_slug_unique'); } catch (\Exception $e) {}
            if (Schema::hasColumn('categories', 'slug'))       $table->dropColumn('slug');
            if (Schema::hasColumn('categories', 'sort_order')) $table->dropColumn('sort_order');
            if (Schema::hasColumn('categories', 'is_active'))  $table->dropColumn('is_active');
        });
    }
};
