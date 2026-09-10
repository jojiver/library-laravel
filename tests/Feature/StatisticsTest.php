<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_library_statistics(): void
    {
        Book::factory()->create(['available_quantity' => 3]);
        Book::factory()->unavailable()->create(['quantity' => 2]);

        $book = Book::factory()->create(['available_quantity' => 10]);

        Borrowing::factory()->count(4)->create(['book_id' => $book->id, 'status' => 'borrowed']);
        Borrowing::factory()->count(3)->returned()->create(['book_id' => $book->id]);

        $response = $this->getJson('/api/library/statistics');

        $response->assertStatus(200)
            ->assertJson([
                'total_books' => 3,
                'available_books' => 2,
                'unavailable_books' => 1,
                'total_borrowings' => 7,
                'active_borrowings' => 4,
                'returned_borrowings' => 3,
            ]);
    }
}