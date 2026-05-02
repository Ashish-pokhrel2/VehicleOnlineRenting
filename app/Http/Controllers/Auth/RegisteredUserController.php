<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PhoneValidationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $countryCode = $request->input('country_code', 'NP');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_digits' => [
                'required',
                'string',
                'regex:/^\d{10}$/',
            ],
            'country_code' => ['required', 'string', 'in:NP'], // Add more country codes as needed
            'role' => ['required', 'in:customer,vendor'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Combine country prefix with digits to create full phone number
        $fullPhoneNumber = PhoneValidationService::formatPhoneNumber(
            $countryCode,
            $request->phone_digits
        );

        // Check uniqueness of full phone number for this country
        $existingUser = User::where('phone', $fullPhoneNumber)
            ->where('country_code', $countryCode)
            ->first();

        if ($existingUser) {
            throw ValidationException::withMessages([
                'phone_digits' => ['This phone number is already registered.'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $fullPhoneNumber,
            'country_code' => $countryCode,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

       return redirect('/');
    }
}
