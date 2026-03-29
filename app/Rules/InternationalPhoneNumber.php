<?php

namespace App\Rules;

use App\Services\PhoneValidationService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class InternationalPhoneNumber implements ValidationRule
{
    protected string $countryCode;

    /**
     * Create a new rule instance.
     */
    public function __construct(string $countryCode = 'NP')
    {
        $this->countryCode = $countryCode;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $format = PhoneValidationService::getCountryFormat($this->countryCode);
        
        if (!$format) {
            $fail("The country code {$this->countryCode} is not supported.");
            return;
        }

        if (!PhoneValidationService::validate($value, $this->countryCode)) {
            $fail("The :attribute must be a valid phone number for {$format['description']}. Example: {$format['example']}");
        }
    }
}
