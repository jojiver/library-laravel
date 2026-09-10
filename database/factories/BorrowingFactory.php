<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'borrower_name' => fake()->name(),
            'borrower_email' => fake()->unique()->safeEmail(),
            'borrowed_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'returned_at' => null,
            'status' => 'borrowed',
        ];
    }

    /**
     * Mark the borrowing as returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => now()->subDays(fake()->numberBetween(1, 5)),
            'status' => 'returned',
        ]);
    }
}