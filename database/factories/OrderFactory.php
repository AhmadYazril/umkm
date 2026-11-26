<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(25000, 200000);
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $seq = $this->faker->unique()->numberBetween(1, 999);

        return [
            'code' => 'NCM-'.$date->format('Ymd').'-'.str_pad((string) $seq, 3, '0', STR_PAD_LEFT),
            'customer_name' => $this->faker->name(),
            'customer_phone' => '08'.$this->faker->numerify('##########'),
            'order_type' => $this->faker->randomElement(['dine_in', 'take_away', 'pre_order']),
            'order_channel' => Order::CHANNEL_DIRECT,
            'barcode_type' => Order::BARCODE_QR,
            'table_id' => null,
            'notes' => $this->faker->optional()->sentence(),
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'payment_method' => $this->faker->randomElement(['cash', 'qris', 'transfer']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'ready', 'completed', 'cancelled']),
            'pickup_at' => null,
            'created_at' => $date,
        ];
    }

    public function zendo(): static
    {
        return $this->state(fn (): array => [
            'order_type' => 'take_away',
            'order_channel' => Order::CHANNEL_ZENDO,
            'barcode_type' => null,
            'notes' => '[KURIR ZENDO] Input Kasir',
        ]);
    }

    public function grab(): static
    {
        return $this->state(fn (): array => [
            'order_type' => 'take_away',
            'order_channel' => Order::CHANNEL_GRAB,
            'barcode_type' => null,
            'notes' => '[GRABFOOD] Input Kasir',
        ]);
    }

    public function linearBarcode(): static
    {
        return $this->state(fn (): array => [
            'order_channel' => Order::CHANNEL_DIRECT,
            'barcode_type' => Order::BARCODE_LINEAR,
        ]);
    }
}
