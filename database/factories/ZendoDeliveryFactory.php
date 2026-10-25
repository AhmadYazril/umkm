<?php

namespace Database\Factories;

use App\Models\ZendoDelivery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ZendoDelivery>
 */
class ZendoDeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => null,
            'order_code' => 'NCM-'.now()->format('Ymd').'-'.fake()->unique()->numberBetween(100, 999),
            'courier_index' => 1,
            'courier_name' => 'Kurir Zendo 1',
            'courier_phone' => '6285712345678',
            'tracking_token' => ZendoDelivery::generateToken(),
            'courier_token' => ZendoDelivery::generateToken(),
            'status' => 'assigned',
            'customer_name' => fake()->name(),
            'delivery_address' => fake()->streetAddress().', Tulungagung',
            'destination_lat' => -8.0995,
            'destination_lng' => 111.8965,
            'gps_suspicious_count' => 0,
        ];
    }

    /**
     * Courier has picked up the order and is on the way.
     */
    public function pickedUp(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'picked_up',
            'picked_up_at' => now(),
        ]);
    }

    /**
     * Courier is close to the destination.
     */
    public function almostDone(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'almost_done',
            'picked_up_at' => now()->subMinutes(10),
        ]);
    }

    /**
     * Delivery has been completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'picked_up_at' => now()->subMinutes(20),
            'completed_at' => now(),
        ]);
    }
}
