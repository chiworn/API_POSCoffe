<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'     => \App\Models\Product::factory(),
            'total_qty'      => $this->faker->numberBetween(1, 5),
            'total_amount'   => $this->faker->randomFloat(2, 5, 50),
            'payment_method' => $this->faker->randomElement(['cash', 'aba', 'wing']),
        ];
    }
}
