<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
            'category' => fake()->randomElement(['Programming', 'Self-Help', 'Productivity', 'Science']),
            'published_year' => fake()->numberBetween(1900, 2025),
            'quantity' => 5,
            'available_quantity' => 5,
            'status' => 'available',
        ];
    }

    /**
     * Mark the book as unavailable with no copies left.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_quantity' => 0,
            'status' => 'unavailable',
        ]);
    }
}