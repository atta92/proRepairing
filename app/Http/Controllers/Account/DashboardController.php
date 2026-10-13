<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $customer = $user->customer;

        $repairOrders = $customer
            ? $customer->orders()->with(['device', 'items'])->latest()->take(5)->get()
            : collect();

        $shopOrders = $customer
            ? $customer->shopOrders()->with('items')->latest()->take(5)->get()
            : collect();

        return view('account.dashboard', compact('user', 'customer', 'repairOrders', 'shopOrders'));
    }
}
