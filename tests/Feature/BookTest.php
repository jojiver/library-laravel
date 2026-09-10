<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function bookPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn' => '9780132350884',
            'category' => 'Programming',
            'published_year' => 2008,
            'quantity' => 5,
            'available_quantity' => 5,
            'status' => 'available',
        ], $overrides);
    }

    public function test_can_create_a_book(): void
    {
        $response = $this->postJson('/api/books', $this->bookPayload());

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Clean Code')
            ->assertJsonPath('data.author', 'Robert C. Martin')
            ->assertJsonPath('data.isbn', '9780132350884')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'author',
                    'isbn',
                    'category',
                    'published_year',
                    'quantity',
                    'available_quantity',
                    'status',
                ],
            ]);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_rejects_invalid_book_data(): void
    {
        $response = $this->postJson('/api/books', [
            'title' => '',
            'author' => '',
            'isbn' => '',
            'category' => '',
            'published_year' => 'not-a-year',
            'quantity' => 0,
            'available_quantity' => -1,
            'status' => 'invalid-status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title',
                'author',
                'isbn',
                'category',
                'published_year',
                'quantity',
                'available_quantity',
                'status',
            ]);
    }

    public function test_rejects_available_quantity_greater_than_quantity(): void
    {
        $response = $this->postJson('/api/books', $this->bookPayload([
            'quantity' => 2,
            'available_quantity' => 3,
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('available_quantity');
    }

    public function test_rejects_duplicate_isbn(): void
    {
        Book::factory()->create(['isbn' => '9780132350884']);

        $response = $this->postJson('/api/books', $this->bookPayload(['isbn' => '9780132350884']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('isbn');
    }

    public function test_returns_paginated_book_list(): void
    {
        Book::factory()->count(25)->create();

        $response = $this->getJson('/api/books?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.last_page', 3)
            ->assertJsonPath('meta.current_page', 1);
    }

    public function test_returns_a_specific_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonPath('data.title', $book->title);
    }

    public function test_returns_404_for_non_existent_book(): void
    {
        $response = $this->getJson('/api/books/does-not-exist');

        $response->assertStatus(404)
            ->assertJsonPath('message', 'Book not found.');
    }

    public function test_can_update_a_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/api/books/{$book->id}", $this->bookPayload([
            'title' => 'Refactoring',
            'isbn' => '9780134757599',
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $book->id)
            ->assertJsonPath('data.title', 'Refactoring')
            ->assertJsonPath('data.isbn', '9780134757599');

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Refactoring',
        ]);
    }

    public function test_update_allows_book_to_keep_existing_isbn(): void
    {
        $book = Book::factory()->create(['isbn' => '9780132350884']);

        $response = $this->putJson("/api/books/{$book->id}", $this->bookPayload([
            'isbn' => '9780132350884',
        ]));

        $response->assertStatus(200)->assertJsonPath('data.isbn', '9780132350884');
    }

    public function test_update_rejects_isbn_used_by_another_book(): void
    {
        $book = Book::factory()->create(['isbn' => '9780132350884']);
        $other = Book::factory()->create();

        $response = $this->putJson("/api/books/{$book->id}", $this->bookPayload([
            'isbn' => $other->isbn,
        ]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('isbn');
    }

    public function test_can_delete_a_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Book deleted successfully.');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cannot_delete_book_with_active_borrowings(): void
    {
        $book = Book::factory()->create();
        $book->borrowings()->create([
            'borrower_name' => 'John Doe',
            'borrower_email' => 'john@example.com',
            'borrowed_at' => now(),
            'status' => 'borrowed',
        ]);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(409)
            ->assertJsonPath('message', 'This book cannot be deleted while it still has active borrowings.');

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_search_and_filters_are_combinable(): void
    {
        Book::factory()->create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'category' => 'Programming',
            'status' => 'available',
        ]);

        Book::factory()->create([
            'title' => 'Clean Architecture',
            'author' => 'Robert C. Martin',
            'category' => 'Science',
            'status' => 'unavailable',
        ]);

        Book::factory()->create([
            'title' => 'Deep Work',
            'author' => 'Cal Newport',
            'category' => 'Productivity',
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/books?search=clean&category=Programming&status=available');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Clean Code');
    }

    public function test_search_matches_title_author_or_isbn(): void
    {
        Book::factory()->create(['title' => 'Some Random Title', 'author' => 'Jane Author', 'isbn' => '1111111111111']);
        Book::factory()->create(['title' => 'Another Book', 'author' => 'Marc Searchable', 'isbn' => '2222222222222']);
        Book::factory()->create(['title' => 'Third Book', 'author' => 'Who Cares', 'isbn' => '3333333333339']);

        $byAuthor = $this->getJson('/api/books?search=Searchable');
        $byIsbn = $this->getJson('/api/books?search=3333333333339');

        $byAuthor->assertJsonCount(1, 'data')->assertJsonPath('data.0.author', 'Marc Searchable');
        $byIsbn->assertJsonCount(1, 'data')->assertJsonPath('data.0.isbn', '3333333333339');
    }
}