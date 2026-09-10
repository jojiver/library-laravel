<?php

namespace App\Repositories\Contracts;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{
    /**
     * Paginate books, optionally filtered by search/category/status/author.
     *
     * @param  array<string, string|null>  $filters
     */
    public function paginate(array $filters = [], ?int $perPage = 15): LengthAwarePaginator;

    public function find(string $id): ?Book;

    public function findOrFail(string $id): Book;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Book;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Book $book, array $data): Book;

    public function delete(Book $book): bool;

    public function count(): int;

    public function countByStatus(string $status): int;
}