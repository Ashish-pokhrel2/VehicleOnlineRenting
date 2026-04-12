<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'phone', 'country_code', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicles::class, 'vendor_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class, 'customer_id');
    }

    public function vendorBookings(): HasMany
    {
        return $this->hasMany(Bookings::class, 'vendor_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'customer_id');
    }

    public function vendorReviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'vendor_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function isVendor(): bool
    {
        return $this->role === UserRole::VENDOR;
    }

    public function isCustomer(): bool
    {
        return $this->role === UserRole::CUSTOMER;
    }
}
