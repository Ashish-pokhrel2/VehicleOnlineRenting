<?php

namespace App\Http\Requests\Vehicles;

use App\Enums\UserRole;
use App\Enums\VehicleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateVehiclesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vehicle = $this->route('vehicle');

        return $this->user()?->role === UserRole::VENDOR
            && $this->user()->id === $vehicle->vendor_id;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::enum(VehicleType::class)],
            'category' => ['sometimes', 'string', 'max:255'],
            'price_per_day' => ['sometimes', 'numeric', 'min:0', 'decimal:0,2'],
            'image' => ['sometimes', 'nullable', File::image()->max(5 * 1024)],
            'features' => ['nullable', 'array'],
            'description' => ['sometimes', 'string'],
            'seats' => ['nullable', 'integer', 'min:1'],
            'transmission' => ['nullable', 'string', 'max:255'],
            'fuel' => ['nullable', 'string', 'max:255'],
            'available' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.enum' => 'The vehicle type must be Car, Bike, Scooter, or Van.',
            'price_per_day.min' => 'The price per day must be a positive number.',
        ];
    }
}
