<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->vendor) {
            return redirect()->route('seller.home');
        }

        return view('seller.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'shop_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:2000'],
            'payout_details' => ['nullable', 'string', 'max:2000'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:20'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'is_admin' => false,
            ]);

            Vendor::query()->create([
                'user_id' => $user->id,
                'name' => $validated['shop_name'],
                'slug' => Vendor::uniqueSlug($validated['shop_name']),
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'description' => $validated['description'] ?? null,
                'payout_details' => $validated['payout_details'] ?? null,
                'address_line_1' => $validated['address_line_1'] ?? null,
                'city' => $validated['city'] ?? null,
                'postcode' => $validated['postcode'] ?? null,
                'status' => 'pending',
                'commission_rate' => 10,
                'is_platform' => false,
                'country' => 'GB',
            ]);

            return $user;
        });

        Auth::login($user);
        $user->load('vendor');

        return redirect()
            ->route('seller.home')
            ->with('success', 'Thanks — your seller application is pending approval.');
    }
}
