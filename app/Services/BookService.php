<?php

namespace App\Services;

use App\Exceptions\BookHasActiveBorrowingsException;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\BorrowingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $data['book_image'] = $this->storeImage($data['book_image'] ?? null);

        return $this->bookRepository->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateBook(string $id, array $data): Book
    {
        $book = $this->bookRepository->findOrFail($id);

        if (array_key_exists('book_image', $data)) {
            $data['book_image'] = $this->storeImage($data['book_image']);

            if ($data['book_image'] !== null && $book->book_image !== null) {
                Storage::disk('public')->delete($book->book_image);
            }
        }

        return $this->bookRepository->update($book, $data);
    }

    /**
     * Store an uploaded book image and return its storage path.
     */
    private function storeImage(mixed $image): ?string
    {
        if (! $image instanceof UploadedFile) {
            return $image;
        }

        return $image->store('books', 'public');
    }

    public function deleteBook(string $id): void
    {
        $book = $this->bookRepository->findOrFail($id);

        if ($this->borrowingRepository->existsActiveForBook($book->id)) {
            throw BookHasActiveBorrowingsException::create();
        }

        if ($book->book_image !== null) {
            Storage::disk('public')->delete($book->book_image);
        }

        $this->bookRepository->delete($book);
    }
}