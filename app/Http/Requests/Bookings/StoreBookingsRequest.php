<?php

namespace App\Http\Requests\Bookings;

use App\Enums\UserRole;
use App\Models\Bookings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBookingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::CUSTOMER;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $overlapping = Bookings::where('vehicle_id', $this->vehicle_id)
                    ->where('status', '!=', 'Cancelled')
                    ->where(function ($query) {
                        $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                            ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                            ->orWhere(function ($q) {
                                $q->where('start_date', '<=', $this->start_date)
                                    ->where('end_date', '>=', $this->end_date);
                            });
                    })
                    ->exists();

                if ($overlapping) {
                    $validator->errors()->add(
                        'vehicle_id',
                        'This vehicle is already booked for the selected dates.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.after_or_equal' => 'The start date must be today or a future date.',
            'end_date.after' => 'The end date must be after the start date.',
        ];
    }
}
