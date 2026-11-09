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
        if (! Schema::hasTable('reservation_fields')) {
            Schema::create('reservation_fields', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->string('field_key')->unique();
                $table->string('type')->default('text'); // text, tel, date, time, number, textarea, photo, checkbox
                $table->string('placeholder')->nullable();
                $table->json('options')->nullable(); // For checkbox choices
                $table->boolean('is_required')->default(true);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed initial default 6 fields
            $now = now();
            DB::table('reservation_fields')->insert([
                [
                    'label' => 'Nama Lengkap',
                    'field_key' => 'name',
                    'type' => 'text',
                    'placeholder' => 'Khanzahamida',
                    'options' => null,
                    'is_required' => true,
                    'sort_order' => 1,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'label' => 'Nomor WhatsApp',
                    'field_key' => 'phone',
                    'type' => 'tel',
                    'placeholder' => '08xxxxxxxxxx',
                    'options' => null,
                    'is_required' => true,
                    'sort_order' => 2,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'label' => 'Tanggal Kedatangan',
                    'field_key' => 'date',
                    'type' => 'date',
                    'placeholder' => null,
                    'options' => null,
                    'is_required' => true,
                    'sort_order' => 3,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'label' => 'Jam Kedatangan',
                    'field_key' => 'time',
                    'type' => 'time',
                    'placeholder' => '16:00',
                    'options' => null,
                    'is_required' => true,
                    'sort_order' => 4,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'label' => 'Jumlah Tamu',
                    'field_key' => 'guests',
                    'type' => 'number',
                    'placeholder' => 'Contoh: 4',
                    'options' => null,
                    'is_required' => true,
                    'sort_order' => 5,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'label' => 'Catatan Tambahan',
                    'field_key' => 'notes',
                    'type' => 'textarea',
                    'placeholder' => 'Tolong siapkan meja dekat colokan / dekorasi santai',
                    'options' => null,
                    'is_required' => false,
                    'sort_order' => 6,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_fields');
    }
};
