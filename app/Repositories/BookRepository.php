<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{
    /**
     * Paginate books, optionally filtered by search/category/status/author.
     *
     * @param  array<string, string|null>  $filters
     */
    public function paginate(array $filters = [], ?int $perPage = 15): LengthAwarePaginator
    {
        $query = Book::query()->orderByDesc('created_at');

        if (! empty($filters['search'])) {
            $query->where(function ($builder) use ($filters) {
                $builder->where('title', 'like', '%'.$filters['search'].'%')
                    ->orWhere('author', 'like', '%'.$filters['search'].'%')
                    ->orWhere('isbn', 'like', '%'.$filters['search'].'%');
            });
        }

        if (! empty($filters['author'])) {
            $query->where('author', 'like', '%'.$filters['author'].'%');
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage ?? 15);
    }

    public function find(string $id): ?Book
    {
        return Book::find($id);
    }

    public function findOrFail(string $id): Book
    {
        return Book::findOrFail($id);
    }

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);

        return $book->refresh();
    }

    public function delete(Book $book): bool
    {
        return (bool) $book->delete();
    }

    public function count(): int
    {
        return Book::count();
    }

    public function countByStatus(string $status): int
    {
        return Book::where('status', $status)->count();
    }
}