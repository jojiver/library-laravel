<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService,
    ) {}

    /**
     * Display a paginated, searchable and filterable list of books.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $books = $this->bookService->listBooks(
            $request->only(['search', 'category', 'status', 'author']),
            $request->integer('per_page'),
        );

        return BookResource::collection($books);
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    /**
     * Display the specified book.
     */
    public function show(string $id): BookResource
    {
        return new BookResource($this->bookService->getBook($id));
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, string $id): BookResource
    {
        $book = $this->bookService->updateBook($id, $request->validated());

        return new BookResource($book);
    }

    /**
     * Remove the specified book.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->bookService->deleteBook($id);

        return response()->json(['message' => 'Book deleted successfully.']);
    }
}