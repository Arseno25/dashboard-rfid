<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name' => fake()->unique()->words(3, true),
            'price' => fake()->numberBetween(15000, 450000),
            'stock' => fake()->numberBetween(0, 120),
            'description' => fake()->paragraph(),
            'is_enabled' => fake()->boolean(85),
            'category_id' => Category::factory(),
        ];
    }
}
