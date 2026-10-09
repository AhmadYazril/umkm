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
        if (!Schema::hasTable('menu_options')) {
            Schema::create('menu_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
                $table->string('name');
                $table->unsignedInteger('price')->default(0);
                $table->enum('type', ['variant', 'addon'])->default('variant');
                $table->boolean('is_sample')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_options');
    }
};
