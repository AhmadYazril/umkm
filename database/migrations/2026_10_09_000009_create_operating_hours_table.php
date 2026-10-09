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
        if (!Schema::hasTable('operating_hours')) {
            Schema::create('operating_hours', function (Blueprint $table) {
                $table->id();
                $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday...6=Saturday
                $table->string('day_name');
                $table->time('open_time')->nullable();
                $table->time('close_time')->nullable();
                $table->boolean('is_closed')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_hours');
    }
};
