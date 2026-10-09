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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->enum('order_type', ['dine_in', 'take_away', 'pre_order'])->default('dine_in');
                $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->unsignedInteger('subtotal');
                $table->unsignedInteger('total');
                $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
                $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
                $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])->default('pending');
                $table->dateTime('pickup_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
