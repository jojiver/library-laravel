<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'borrower_name' => $this->borrower_name,
            'borrower_email' => $this->borrower_email,
            'borrowed_at' => $this->borrowed_at?->toIso8601String(),
            'returned_at' => $this->returned_at?->toIso8601String(),
            'status' => $this->status,
        ];
    }
}