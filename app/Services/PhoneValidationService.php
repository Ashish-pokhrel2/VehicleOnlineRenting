<?php

namespace App\Services;

class PhoneValidationService
{
    /**
     * Supported country phone formats
     * Format: 'COUNTRY_CODE' => ['prefix' => '+XXX', 'digits' => number_of_digits, ...]
     */
    protected static array $countryFormats = [
        'NP' => [
            'name' => 'Nepal',
            'prefix' => '+977',
            'digits' => 10,
            'regex' => '/^\+977\d{10}$/',
            'example' => '+9779812345678',
            'description' => 'Nepal (+977 followed by 10 digits)',
        ],
        // Future expansion examples:
        // 'IN' => [
        //     'name' => 'India',
        //     'prefix' => '+91',
        //     'digits' => 10,
        //     'regex' => '/^\+91\d{10}$/',
        //     'example' => '+919812345678',
        //     'description' => 'India (+91 followed by 10 digits)',
        // ],
        // 'US' => [
        //     'name' => 'United States',
        //     'prefix' => '+1',
        //     'digits' => 10,
        //     'regex' => '/^\+1\d{10}$/',
        //     'example' => '+12025551234',
        //     'description' => 'United States (+1 followed by 10 digits)',
        // ],
    ];

    /**
     * Validate phone number for a specific country
     */
    public static function validate(string $phone, string $countryCode): bool
    {
        if (!isset(self::$countryFormats[$countryCode])) {
            return false;
        }

        return preg_match(self::$countryFormats[$countryCode]['regex'], $phone) === 1;
    }

    /**
     * Get supported countries
     */
    public static function getSupportedCountries(): array
    {
        return array_keys(self::$countryFormats);
    }

    /**
     * Get country format information
     */
    public static function getCountryFormat(string $countryCode): ?array
    {
        return self::$countryFormats[$countryCode] ?? null;
    }

    /**
     * Get all country formats
     */
    public static function getAllFormats(): array
    {
        return self::$countryFormats;
    }

    /**
     * Extract country code from phone number
     */
    public static function extractCountryCode(string $phone): ?string
    {
        foreach (self::$countryFormats as $code => $format) {
            if (preg_match($format['regex'], $phone)) {
                return $code;
            }
        }
        return null;
    }

    /**
     * Format phone number by combining country code prefix with digits
     */
    public static function formatPhoneNumber(string $countryCode, string $digits): string
    {
        $format = self::getCountryFormat($countryCode);
        if (!$format) {
            return $digits;
        }
        
        return $format['prefix'] . $digits;
    }

    /**
     * Validate just the digits part (without country prefix)
     */
    public static function validateDigits(string $digits, string $countryCode): bool
    {
        $format = self::getCountryFormat($countryCode);
        if (!$format) {
            return false;
        }
        
        return preg_match('/^\d{' . $format['digits'] . '}$/', $digits) === 1;
    }
}
