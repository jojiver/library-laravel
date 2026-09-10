<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Services\BorrowingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BorrowingController extends Controller
{
    public function __construct(
        private readonly BorrowingService $borrowingService,
    ) {}

    /**
     * Display a paginated, filterable list of borrowings.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $borrowings = $this->borrowingService->listBorrowings(
            $request->only(['status', 'borrower_email']),
            $request->integer('per_page'),
        );

        return BorrowingResource::collection($borrowings);
    }

    /**
     * Borrow a book.
     */
    public function store(StoreBorrowingRequest $request): JsonResponse
    {
        $borrowing = $this->borrowingService->borrowBook($request->validated());

        return (new BorrowingResource($borrowing))->response()->setStatusCode(201);
    }

    /**
     * Display the specified borrowing.
     */
    public function show(string $id): BorrowingResource
    {
        return new BorrowingResource($this->borrowingService->getBorrowing($id));
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook(string $id): BorrowingResource
    {
        $borrowing = $this->borrowingService->returnBook($id);

        return new BorrowingResource($borrowing);
    }
}