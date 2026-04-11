<?php

namespace App\Http\Requests\Reviews;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::CUSTOMER;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.min' => 'The rating must be between 1 and 5.',
            'rating.max' => 'The rating must be between 1 and 5.',
            'comment.min' => 'The comment must be at least 10 characters.',
        ];
    }
}
