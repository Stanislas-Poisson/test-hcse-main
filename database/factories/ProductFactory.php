<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'image' => 'products/placeholder.jpg',
            'price' => fake()->randomFloat(2, 5, 999),
            'state' => 'draft',
        ];
    }

    public function published(): static
    {
        return $this->state(['state' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(['state' => 'draft']);
    }

    public function invisible(): static
    {
        return $this->state(['state' => 'invisible']);
    }
}
