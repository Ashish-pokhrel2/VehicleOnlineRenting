<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NepaliPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validate Nepali phone number format: +977[10 digits]
        if (!preg_match('/^\+977\d{10}$/', $value)) {
            $fail('The :attribute must be a valid Nepali phone number in the format +977XXXXXXXXXX (10 digits after +977).');
        }
    }
}
