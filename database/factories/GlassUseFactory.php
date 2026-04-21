<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlassUse>
 */
class GlassUseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'    => \App\Models\Product::factory(),
            'glass_id'      => \App\Models\Glass::factory(),
            'cashier_id'    => \App\Models\User::factory(),
            'quantity_used' => $this->faker->numberBetween(1, 2),
        ];
    }
}
