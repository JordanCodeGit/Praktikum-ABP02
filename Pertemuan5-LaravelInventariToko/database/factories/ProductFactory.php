<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        'name'        => fake()->words(3, true),
        'category'    => fake()->randomElement(['Elektronik', 'Makanan', 'Minuman', 'Pakaian']),
        'stock'       => fake()->numberBetween(1, 100),
        'price'       => fake()->numberBetween(5000, 500000),
        'description' => fake()->sentence(),
    ];
}
}
