<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin;
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function ensureCustomer(array $attributes = []): Customer
    {
        $payload = array_merge([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: '',
        ], $attributes);

        if (array_key_exists('phone', $payload) && $payload['phone'] === null) {
            $payload['phone'] = '';
        }

        $customer = $this->customer;

        if ($customer) {
            $customer->fill($payload);
            $customer->save();

            return $customer->fresh();
        }

        return $this->customer()->create($payload);
    }
}
