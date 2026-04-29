<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function createCustomer(): View
    {
        return $this->createForRole(UserRole::CUSTOMER, 'login');
    }

    public function createVendor(): View
    {
        return $this->createForRole(UserRole::VENDOR, 'vendor.login');
    }

    public function createAdmin(): View
    {
        return $this->createForRole(UserRole::ADMIN, 'admin.login');
    }

    public function storeCustomer(LoginRequest $request): RedirectResponse
    {
        return $this->storeForRole($request, 'dashboard');
    }

    public function storeVendor(LoginRequest $request): RedirectResponse
    {
        return $this->storeForRole($request, 'dashboard');
    }

    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        return $this->storeForRole($request, 'admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function createForRole(UserRole $role, string $loginRoute): View
    {
        return view('auth.login', [
            'role' => $role,
            'loginRoute' => $loginRoute,
        ]);
    }

    private function storeForRole(LoginRequest $request, string $redirectRoute): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route($redirectRoute, absolute: false));
    }
}
