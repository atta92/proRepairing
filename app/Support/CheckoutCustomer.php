<?php

namespace App\Support;

use App\Models\Customer;
use App\Models\User;

class CheckoutCustomer
{
    /**
     * @param  array{name: string, email: string, phone: string, address_line_1?: ?string, address_line_2?: ?string, city?: ?string, postcode?: ?string}  $data
     */
    public static function resolve(?User $user, array $data): Customer
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: '',
            'address_line_1' => $data['address_line_1'] ?? null,
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'] ?? null,
            'postcode' => $data['postcode'] ?? null,
        ];

        if ($user && ! $user->is_admin) {
            $customer = $user->ensureCustomer($payload);

            $user->fill([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'] ?: null,
            ])->save();

            return $customer;
        }

        $customer = Customer::query()->where('email', $payload['email'])->first();

        if ($customer) {
            // Don't steal a linked customer account from another user.
            if ($customer->user_id && (! $user || $customer->user_id !== $user->id)) {
                $customer = Customer::query()->create(array_merge($payload, [
                    'user_id' => $user?->id,
                ]));

                return $customer;
            }

            $customer->fill($payload);
            if ($user && ! $customer->user_id) {
                $customer->user_id = $user->id;
            }
            $customer->save();

            return $customer->fresh();
        }

        return Customer::query()->create(array_merge($payload, [
            'user_id' => ($user && ! $user->is_admin) ? $user->id : null,
        ]));
    }
}
