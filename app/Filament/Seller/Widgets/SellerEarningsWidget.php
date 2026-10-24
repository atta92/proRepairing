<?php

namespace App\Filament\Seller\Widgets;

use App\Models\ShopOrderItem;
use App\Models\VendorPayout;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SellerEarningsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $vendor = Filament::auth()->user()?->vendor;

        if (! $vendor) {
            return [];
        }

        $paidStatuses = ['paid', 'processing', 'shipped', 'collected'];

        $gross = (float) ShopOrderItem::query()
            ->where('vendor_id', $vendor->id)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->selectRaw('COALESCE(SUM(price * quantity), 0) as total')
            ->value('total');

        $net = (float) ShopOrderItem::query()
            ->where('vendor_id', $vendor->id)
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->sum('vendor_net');

        $unpaid = (float) ShopOrderItem::query()
            ->where('vendor_id', $vendor->id)
            ->whereDoesntHave('payoutItem')
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->sum('vendor_net');

        $paidOut = (float) VendorPayout::query()
            ->where('vendor_id', $vendor->id)
            ->where('status', 'paid')
            ->sum('amount');

        return [
            Stat::make('Gross sales', '£'.number_format($gross, 2)),
            Stat::make('Your net', '£'.number_format($net, 2))
                ->description('After '.$vendor->commission_rate.'% commission'),
            Stat::make('Unpaid balance', '£'.number_format($unpaid, 2)),
            Stat::make('Paid out', '£'.number_format($paidOut, 2)),
        ];
    }
}
