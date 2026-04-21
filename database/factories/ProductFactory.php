<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'         => \App\Models\Category::factory(),
            'product_namekhmer'   => 'ផលិតផល ' . $this->faker->word(),
            'product_nameenglish' => $this->faker->word(),
            'price'               => $this->faker->randomFloat(2, 1, 100),
            'stock'               => $this->faker->numberBetween(0, 500),
            'status'              => $this->faker->randomElement(['active', 'inactive']),
            'image'               => $this->faker->imageUrl(),
            'image_public_id'     => $this->faker->uuid(),
        ];
    }
}
