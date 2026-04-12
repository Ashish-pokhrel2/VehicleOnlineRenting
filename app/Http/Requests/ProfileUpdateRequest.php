<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\InternationalPhoneNumber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $countryCode = $this->user()->country_code ?? 'NP';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
                new InternationalPhoneNumber($countryCode),
                Rule::unique('users')->where(function ($query) use ($countryCode) {
                    return $query->where('country_code', $countryCode);
                })->ignore($this->user()->id),
            ],
        ];
    }
}
