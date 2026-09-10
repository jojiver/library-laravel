<?php

namespace App\Services;

use App\Exceptions\AlreadyReturnedException;
use App\Exceptions\BookUnavailableException;
use App\Models\Book;
use App\Models\Borrowing;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\BorrowingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function __construct(
        private readonly BorrowingRepositoryInterface $borrowingRepository,
        private readonly BookRepositoryInterface $bookRepository,
    ) {}

    /**
     * @param  array<string, string|null>  $filters
     */
    public function listBorrowings(array $filters = [], ?int $perPage = null): LengthAwarePaginator
    {
        return $this->borrowingRepository->paginate($filters, $perPage);
    }

    public function getBorrowing(string $id): Borrowing
    {
        return $this->borrowingRepository->findOrFail($id);
    }

    /**
     * @param  array<string, string>  $data
     */
    public function borrowBook(array $data): Borrowing
    {
        return DB::transaction(function () use ($data): Borrowing {
            $book = $this->bookRepository->findOrFail($data['book_id']);

            $this->assertAvailable($book);

            $borrowing = $this->borrowingRepository->create([
                'book_id' => $book->id,
                'borrower_name' => $data['borrower_name'],
                'borrower_email' => $data['borrower_email'],
                'borrowed_at' => now(),
                'status' => 'borrowed',
            ]);

            $this->decreaseAvailability($book);

            return $borrowing;
        });
    }

    public function returnBook(string $id): Borrowing
    {
        return DB::transaction(function () use ($id): Borrowing {
            $borrowing = $this->borrowingRepository->findOrFail($id);

            if ($borrowing->status === 'returned') {
                throw AlreadyReturnedException::create();
            }

            $book = $this->bookRepository->findOrFail($borrowing->book_id);

            $borrowing = $this->borrowingRepository->update($borrowing, [
                'status' => 'returned',
                'returned_at' => now(),
            ]);

            $this->increaseAvailability($book);

            return $borrowing;
        });
    }

    /**
     * Rule 1 — a book cannot be borrowed when no copies are available.
     */
    private function assertAvailable(Book $book): void
    {
        if ($book->available_quantity <= 0 || $book->status === 'unavailable') {
            throw BookUnavailableException::create();
        }
    }

    /**
     * Rule 2 — borrowing decreases availability and flips status at zero.
     */
    private function decreaseAvailability(Book $book): void
    {
        $available = $book->available_quantity - 1;

        $this->bookRepository->update($book, [
            'available_quantity' => $available,
            'status' => $available <= 0 ? 'unavailable' : 'available',
        ]);
    }

    /**
     * Rule 3 — returning increases availability and restores the status.
     */
    private function increaseAvailability(Book $book): void
    {
        $available = $book->available_quantity + 1;

        $this->bookRepository->update($book, [
            'available_quantity' => $available,
            'status' => $available > 0 ? 'available' : 'unavailable',
        ]);
    }
}