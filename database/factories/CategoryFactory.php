<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_name' => $this->faker->words(2, true),
            'description'   => $this->faker->sentence(),
            'image'         => $this->faker->imageUrl(),
            'image_public_id' => $this->faker->uuid(),
        ];
    }
}
