<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_borrow_an_available_book(): void
    {
        $book = Book::factory()->create(['quantity' => 2, 'available_quantity' => 2]);

        $response = $this->postJson('/api/borrowings', [
            'book_id' => $book->id,
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.book_id', $book->id)
            ->assertJsonPath('data.borrower_name', 'John Doe')
            ->assertJsonPath('data.status', 'borrowed')
            ->assertJsonPath('data.returned_at', null)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'book_id',
                    'borrower_name',
                    'borrower_email',
                    'borrowed_at',
                    'returned_at',
                    'status',
                ],
            ]);

        $this->assertDatabaseCount('borrowings', 1);
    }

    public function test_borrowing_decreases_available_quantity(): void
    {
        $book = Book::factory()->create(['quantity' => 2, 'available_quantity' => 2]);

        $this->postJson('/api/borrowings', [
            'book_id' => $book->id,
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
        ]);

        $this->assertSame(1, $book->refresh()->available_quantity);
    }

    public function test_book_becomes_unavailable_when_last_copy_is_borrowed(): void
    {
        $book = Book::factory()->create(['quantity' => 1, 'available_quantity' => 1]);

        $this->postJson('/api/borrowings', [
            'book_id' => $book->id,
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
        ]);

        $book->refresh();

        $this->assertSame(0, $book->available_quantity);
        $this->assertSame('unavailable', $book->status);
    }

    public function test_rejects_borrowing_when_no_copies_are_available(): void
    {
        $book = Book::factory()->unavailable()->create(['quantity' => 1]);

        $response = $this->postJson('/api/borrowings', [
            'book_id' => $book->id,
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
        ]);

        $response->assertStatus(409)
            ->assertJsonPath('message', 'This book is currently unavailable for borrowing.');

        $this->assertDatabaseCount('borrowings', 0);
    }

    public function test_client_cannot_override_status_or_borrowed_at(): void
    {
        $book = Book::factory()->create(['available_quantity' => 2]);

        $response = $this->postJson('/api/borrowings', [
            'book_id' => $book->id,
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
            'status' => 'returned',
            'borrowed_at' => '2000-01-01 00:00:00',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'borrowed')
            ->assertJsonPath('data.borrowed_at', fn (string $value): bool => str_starts_with($value, now()->format('Y')));

        $this->assertDatabaseHas('borrowings', [
            'id' => $response->json('data.id'),
            'status' => 'borrowed',
        ]);
    }

    public function test_validates_borrowing_request(): void
    {
        $response = $this->postJson('/api/borrowings', [
            'book_id' => 'does-not-exist',
            'borrower_name' => '',
            'borrower_email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['book_id', 'borrower_name', 'borrower_email']);
    }

    public function test_can_return_a_borrowed_book(): void
    {
        $book = Book::factory()->create(['quantity' => 2, 'available_quantity' => 1]);
        $borrowing = Borrowing::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $response = $this->putJson("/api/borrowings/{$borrowing->id}/return");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $borrowing->id)
            ->assertJsonPath('data.status', 'returned')
            ->assertJsonPath('data.returned_at', fn (mixed $value): bool => $value !== null);
    }

    public function test_returning_increases_available_quantity(): void
    {
        $book = Book::factory()->create(['quantity' => 2, 'available_quantity' => 1]);
        $borrowing = Borrowing::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $this->putJson("/api/borrowings/{$borrowing->id}/return");

        $this->assertSame(2, $book->refresh()->available_quantity);
        $this->assertSame('available', $book->refresh()->status);
    }

    public function test_book_status_is_restored_when_last_borrowing_is_returned(): void
    {
        $book = Book::factory()->create(['quantity' => 1, 'available_quantity' => 0, 'status' => 'unavailable']);
        $borrowing = Borrowing::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $this->putJson("/api/borrowings/{$borrowing->id}/return");

        $this->assertSame(1, $book->refresh()->available_quantity);
        $this->assertSame('available', $book->refresh()->status);
    }

    public function test_rejects_returning_the_same_borrowing_twice(): void
    {
        $book = Book::factory()->create(['available_quantity' => 2]);
        $borrowing = Borrowing::factory()->returned()->create(['book_id' => $book->id]);

        $response = $this->putJson("/api/borrowings/{$borrowing->id}/return");

        $response->assertStatus(409)
            ->assertJsonPath('message', 'This borrowing has already been returned.');
    }

    public function test_returns_404_for_non_existent_borrowing(): void
    {
        $response = $this->putJson('/api/borrowings/does-not-exist/return');

        $response->assertStatus(404)
            ->assertJsonPath('message', 'Borrowing not found.');
    }

    public function test_list_borrowings_is_paginated_and_filterable(): void
    {
        $book = Book::factory()->create(['quantity' => 30, 'available_quantity' => 30]);

        Borrowing::factory()->count(20)->create([
            'book_id' => $book->id,
            'borrower_email' => 'john@example.com',
            'status' => 'borrowed',
        ]);

        Borrowing::factory()->count(5)->returned()->create([
            'book_id' => $book->id,
            'borrower_email' => 'jane@example.com',
        ]);

        $response = $this->getJson('/api/borrowings?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.last_page', 3);

        $filtered = $this->getJson('/api/borrowings?status=returned&borrower_email=jane%40example.com&per_page=10');

        $filtered->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.last_page', 1)
            ->assertJsonPath('meta.total', 5);
    }

    public function test_returns_a_specific_borrowing(): void
    {
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create(['book_id' => $book->id]);

        $response = $this->getJson("/api/borrowings/{$borrowing->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $borrowing->id)
            ->assertJsonPath('data.borrower_email', $borrowing->borrower_email);
    }
}