<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'device_name' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('quotes', 'public');
        }

        Quote::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'device_name' => $validated['device_name'],
            'message' => $validated['message'],
            'image_path' => $imagePath,
            'status' => 'new',
        ]);

        return back()->with('success', 'Thanks — we’ve received your quote request and will get back to you shortly.');
    }
}
