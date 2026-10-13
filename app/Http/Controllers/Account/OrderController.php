<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function repairs(Request $request): View
    {
        $customerId = $request->user()->customer?->id;

        $orders = Order::query()
            ->when($customerId, fn ($q) => $q->where('customer_id', $customerId), fn ($q) => $q->whereRaw('1 = 0'))
            ->with(['device.category', 'items'])
            ->latest()
            ->paginate(10);

        return view('account.repairs', compact('orders'));
    }

    public function showRepair(Request $request, Order $order): View
    {
        $this->authorizeCustomerOrder($request, $order->customer_id);
        $order->load(['device.category', 'items', 'customer']);

        return view('account.repair-show', compact('order'));
    }

    public function shop(Request $request): View
    {
        $customerId = $request->user()->customer?->id;

        $orders = ShopOrder::query()
            ->when($customerId, fn ($q) => $q->where('customer_id', $customerId), fn ($q) => $q->whereRaw('1 = 0'))
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('account.shop-orders', compact('orders'));
    }

    public function showShop(Request $request, ShopOrder $shopOrder): View
    {
        $this->authorizeCustomerOrder($request, $shopOrder->customer_id);
        $shopOrder->load(['items', 'customer']);

        return view('account.shop-order-show', ['order' => $shopOrder]);
    }

    private function authorizeCustomerOrder(Request $request, ?int $customerId): void
    {
        $userCustomerId = $request->user()->customer?->id;

        abort_unless($userCustomerId && $customerId === $userCustomerId, 403);
    }
}
