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
        Schema::create('zendo_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->string('order_code')->index();
            $table->unsignedSmallInteger('courier_index')->default(1);
            $table->string('courier_name');
            $table->string('courier_phone', 30)->index();
            $table->string('tracking_token', 64)->unique();
            $table->string('courier_token', 64)->unique();
            $table->string('status', 20)->default('assigned')->index();
            $table->string('customer_name')->nullable();
            $table->text('delivery_address')->nullable();
            $table->decimal('destination_lat', 10, 7)->nullable();
            $table->decimal('destination_lng', 10, 7)->nullable();
            $table->decimal('courier_lat', 10, 7)->nullable();
            $table->decimal('courier_lng', 10, 7)->nullable();
            $table->float('courier_accuracy')->nullable();
            $table->float('courier_heading')->nullable();
            $table->float('courier_speed')->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->json('route_trail')->nullable();
            $table->unsignedInteger('gps_suspicious_count')->default(0);
            $table->string('last_gps_warning')->nullable();
            $table->timestamp('gps_locked_until')->nullable();
            $table->timestamp('courier_verified_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zendo_deliveries');
    }
};
