<?php

namespace App\Services;

use App\Exceptions\BookHasActiveBorrowingsException;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\BorrowingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly BorrowingRepositoryInterface $borrowingRepository,
    ) {}

    /**
     * @param  array<string, string|null>  $filters
     */
    public function listBooks(array $filters = [], ?int $perPage = null): LengthAwarePaginator
    {
        return $this->bookRepository->paginate($filters, $perPage);
    }

    public function getBook(string $id): Book
    {
        return $this->bookRepository->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createBook(array $data): Book
    {
        return $this->bookRepository->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateBook(string $id, array $data): Book
    {
        $book = $this->bookRepository->findOrFail($id);

        return $this->bookRepository->update($book, $data);
    }

    public function deleteBook(string $id): void
    {
        $book = $this->bookRepository->findOrFail($id);

        if ($this->borrowingRepository->existsActiveForBook($book->id)) {
            throw BookHasActiveBorrowingsException::create();
        }

        $this->bookRepository->delete($book);
    }
}