<?php

namespace App\Repositories\Contracts;

use App\Models\Borrowing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BorrowingRepositoryInterface
{
    /**
     * Paginate borrowings, optionally filtered by status / borrower email.
     *
     * @param  array<string, string|null>  $filters
     */
    public function paginate(array $filters = [], ?int $perPage = 15): LengthAwarePaginator;

    public function find(string $id): ?Borrowing;

    public function findOrFail(string $id): Borrowing;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Borrowing;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Borrowing $borrowing, array $data): Borrowing;

    public function count(): int;

    public function countByStatus(string $status): int;

    public function existsActiveForBook(string $bookId): bool;
}