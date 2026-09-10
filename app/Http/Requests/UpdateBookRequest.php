<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:150'],
            'isbn' => ['required', 'string', 'max:50', Rule::unique('books', 'isbn')->ignore($this->route('book'), 'id')],
            'category' => ['required', 'string', 'max:100'],
            'published_year' => ['required', 'integer', 'min:1000', 'max:2999'],
            'quantity' => ['required', 'integer', 'min:1'],
            'available_quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'unavailable'])],
        ];
    }

    /**
     * Ensure available_quantity never exceeds quantity.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $quantity = $validator->safe()->integer('quantity');
                $availableQuantity = $validator->safe()->integer('available_quantity');

                if ($quantity !== null && $availableQuantity !== null && $availableQuantity > $quantity) {
                    $validator->errors()->add(
                        'available_quantity',
                        'The available quantity cannot be greater than the total quantity.',
                    );
                }
            },
        ];
    }
}