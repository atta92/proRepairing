<?php

namespace App\Filament\Widgets;

use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\Vendor;
use App\Models\VendorPayout;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformEarningsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'processing', 'shipped', 'collected'];
        $platformVendorId = Vendor::platform()?->id;

        $shopRevenue = (float) ShopOrder::query()
            ->whereIn('status', $paidStatuses)
            ->sum('total');

        $commissionEarned = (float) ShopOrderItem::query()
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->sum('commission_amount');

        $ownShopNet = $platformVendorId
            ? (float) ShopOrderItem::query()
                ->where('vendor_id', $platformVendorId)
                ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
                ->sum('vendor_net')
            : 0.0;

        $unpaidToSellers = (float) ShopOrderItem::query()
            ->when($platformVendorId, fn ($q) => $q->where('vendor_id', '!=', $platformVendorId))
            ->whereDoesntHave('payoutItem')
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->sum('vendor_net');

        $paidOutToSellers = (float) VendorPayout::query()
            ->where('status', 'paid')
            ->sum('amount');

        return [
            Stat::make('Shop revenue', '£'.number_format($shopRevenue, 2))
                ->description('Paid order totals (Stripe)'),
            Stat::make('Commission earned', '£'.number_format($commissionEarned, 2))
                ->description('Platform cut from seller lines'),
            Stat::make('Own shop net', '£'.number_format($ownShopNet, 2))
                ->description('Platform vendor product sales'),
            Stat::make('Unpaid to sellers', '£'.number_format($unpaidToSellers, 2))
                ->description('Seller nets awaiting payout'),
            Stat::make('Paid out to sellers', '£'.number_format($paidOutToSellers, 2))
                ->description('Completed vendor payouts'),
        ];
    }
}
