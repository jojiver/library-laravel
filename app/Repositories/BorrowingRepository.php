<?php

namespace App\Repositories;

use App\Models\Borrowing;
use App\Repositories\Contracts\BorrowingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BorrowingRepository implements BorrowingRepositoryInterface
{
    /**
     * Paginate borrowings, optionally filtered by status / borrower email.
     *
     * @param  array<string, string|null>  $filters
     */
    public function paginate(array $filters = [], ?int $perPage = 15): LengthAwarePaginator
    {
        $query = Borrowing::query()->orderByDesc('created_at');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['borrower_email'])) {
            $query->where('borrower_email', $filters['borrower_email']);
        }

        return $query->paginate($perPage ?? 15);
    }

    public function find(string $id): ?Borrowing
    {
        return Borrowing::find($id);
    }

    public function findOrFail(string $id): Borrowing
    {
        return Borrowing::findOrFail($id);
    }

    public function create(array $data): Borrowing
    {
        return Borrowing::create($data);
    }

    public function update(Borrowing $borrowing, array $data): Borrowing
    {
        $borrowing->update($data);

        return $borrowing->refresh();
    }

    public function count(): int
    {
        return Borrowing::count();
    }

    public function countByStatus(string $status): int
    {
        return Borrowing::where('status', $status)->count();
    }

    public function existsActiveForBook(string $bookId): bool
    {
        return Borrowing::where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->exists();
    }
}