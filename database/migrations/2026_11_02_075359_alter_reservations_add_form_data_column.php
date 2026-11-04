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
        if (Schema::hasTable('reservations') && ! Schema::hasColumn('reservations', 'form_data')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->json('form_data')->nullable()->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('reservations') && Schema::hasColumn('reservations', 'form_data')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('form_data');
            });
        }
    }
};
