<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\BookingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function show(string $slug): View
    {
        $device = Device::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'category',
                'deviceRepairs' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with('repair'),
            ])
            ->firstOrFail();

        return view('devices.show', compact('device'));
    }

    public function book(Request $request, string $slug, BookingCart $cart): RedirectResponse
    {
        $device = Device::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'device_repair_ids' => ['required', 'array', 'min:1'],
            'device_repair_ids.*' => ['integer'],
        ]);

        $validIds = $device->deviceRepairs()
            ->where('is_active', true)
            ->whereIn('id', $validated['device_repair_ids'])
            ->pluck('id')
            ->all();

        if ($validIds === []) {
            return back()->withErrors(['device_repair_ids' => 'Please select at least one repair.']);
        }

        $cart->put($device->id, $validIds);

        return redirect()->route('checkout.show');
    }
}
