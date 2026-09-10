<?php

namespace App\Services;

use App\Repositories\Contracts\BorrowingRepositoryInterface;
use App\Repositories\Contracts\BookRepositoryInterface;

class StatisticsService
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly BorrowingRepositoryInterface $borrowingRepository,
    ) {}

    /**
     * @return array<string, int>
     */
    public function getStatistics(): array
    {
        return [
            'total_books' => $this->bookRepository->count(),
            'available_books' => $this->bookRepository->countByStatus('available'),
            'unavailable_books' => $this->bookRepository->countByStatus('unavailable'),
            'total_borrowings' => $this->borrowingRepository->count(),
            'active_borrowings' => $this->borrowingRepository->countByStatus('borrowed'),
            'returned_borrowings' => $this->borrowingRepository->countByStatus('returned'),
        ];
    }
}