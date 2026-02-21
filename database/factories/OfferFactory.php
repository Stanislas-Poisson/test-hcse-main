<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => 'offers/placeholder.jpg',
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

    public function hidden(): static
    {
        return $this->state(['state' => 'hidden']);
    }
}
