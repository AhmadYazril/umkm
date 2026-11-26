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
        Schema::table('orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('orders', 'order_channel')) {
                $table->string('order_channel', 30)->default('direct')->index()->after('order_type');
            }
            if (! Schema::hasColumn('orders', 'barcode_type')) {
                $table->string('barcode_type', 20)->nullable()->after('order_channel');
            }
        });

        // Backfill existing orders cleanly so historical records map to the proper channel
        if (Schema::hasColumn('orders', 'order_channel')) {
            DB::table('orders')
                ->where(function ($q): void {
                    $q->where('notes', 'like', '%[KURIR ZENDO]%')
                        ->orWhereNotNull('delivery_address');
                })
                ->update([
                    'order_channel' => 'zendo',
                    'barcode_type' => null,
                ]);

            DB::table('orders')
                ->where('notes', 'like', '%[GRAB%')
                ->update([
                    'order_channel' => 'grab',
                    'barcode_type' => null,
                ]);

            DB::table('orders')
                ->where('order_channel', 'direct')
                ->whereNull('barcode_type')
                ->update([
                    'barcode_type' => 'qr',
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'barcode_type')) {
                $table->dropColumn('barcode_type');
            }
            if (Schema::hasColumn('orders', 'order_channel')) {
                $table->dropIndex(['order_channel']);
                $table->dropColumn('order_channel');
            }
        });
    }
};
