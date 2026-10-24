<?php

namespace App\Services;

use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\Vendor;
use App\Models\VendorPayout;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ShopAuditReport
{
    /**
     * @return list<string>
     */
    public static function paidStatuses(): array
    {
        return ['paid', 'processing', 'shipped', 'collected'];
    }

    /**
     * @return array<int, string>
     */
    public function vendorOptions(): array
    {
        return Vendor::query()
            ->orderByDesc('is_platform')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Vendor $vendor) => [
                $vendor->id => $vendor->is_platform
                    ? $vendor->name.' (Own shop)'
                    : $vendor->name,
            ])
            ->all();
    }

    /**
     * @return array{
     *     shop_revenue: float,
     *     own_shop_gross: float,
     *     own_shop_net: float,
     *     commission_earned: float,
     *     seller_gross: float,
     *     seller_net_total: float,
     *     seller_net_unpaid: float,
     *     paid_out_to_sellers: float,
     *     shipping_retained: float,
     *     line_count: int,
     *     order_count: int,
     *     filtered_gross: float,
     *     filtered_commission: float,
     *     filtered_vendor_net: float,
     *     vendor_id: int|null,
     *     vendor_name: string|null,
     *     is_own_shop: bool
     * }
     */
    public function summary(?CarbonInterface $from = null, ?CarbonInterface $to = null, ?int $vendorId = null): array
    {
        $platformVendorId = Vendor::platform()?->id;
        $vendor = $vendorId ? Vendor::query()->find($vendorId) : null;
        $lines = $this->lineItemsQuery($from, $to, $vendorId)->get();

        $own = $lines->filter(fn (ShopOrderItem $item) => $platformVendorId && (int) $item->vendor_id === (int) $platformVendorId);
        $seller = $lines->reject(fn (ShopOrderItem $item) => $platformVendorId && (int) $item->vendor_id === (int) $platformVendorId);

        $orderIds = $lines->pluck('shop_order_id')->unique()->values();

        $shopRevenue = $vendorId
            ? round((float) $lines->sum(fn (ShopOrderItem $i) => $i->lineTotal()), 2)
            : (float) ShopOrder::query()->whereIn('id', $orderIds)->sum('total');

        $shippingRetained = $vendorId
            ? 0.0
            : (float) ShopOrder::query()->whereIn('id', $orderIds)->sum('shipping_amount');

        $paidOutQuery = VendorPayout::query()
            ->where('status', 'paid')
            ->when($vendorId, fn ($q) => $q->where('vendor_id', $vendorId))
            ->when($from, fn ($q) => $q->whereDate('paid_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('paid_at', '<=', $to));

        $unpaid = (float) $seller
            ->filter(fn (ShopOrderItem $item) => $item->payoutItem === null)
            ->sum('vendor_net');

        $isOwnShop = (bool) ($vendor?->is_platform);

        return [
            'shop_revenue' => round($shopRevenue, 2),
            'own_shop_gross' => round((float) $own->sum(fn (ShopOrderItem $i) => $i->lineTotal()), 2),
            'own_shop_net' => round((float) $own->sum('vendor_net'), 2),
            'commission_earned' => round((float) $seller->sum('commission_amount'), 2),
            'seller_gross' => round((float) $seller->sum(fn (ShopOrderItem $i) => $i->lineTotal()), 2),
            'seller_net_total' => round((float) $seller->sum('vendor_net'), 2),
            'seller_net_unpaid' => round($unpaid, 2),
            'paid_out_to_sellers' => round((float) $paidOutQuery->sum('amount'), 2),
            'shipping_retained' => round($shippingRetained, 2),
            'line_count' => $lines->count(),
            'order_count' => $orderIds->count(),
            'filtered_gross' => round((float) $lines->sum(fn (ShopOrderItem $i) => $i->lineTotal()), 2),
            'filtered_commission' => round((float) $lines->sum('commission_amount'), 2),
            'filtered_vendor_net' => round((float) $lines->sum('vendor_net'), 2),
            'vendor_id' => $vendorId,
            'vendor_name' => $vendor?->name,
            'is_own_shop' => $isOwnShop,
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function detailRows(?CarbonInterface $from = null, ?CarbonInterface $to = null, ?int $vendorId = null): Collection
    {
        $platformVendorId = Vendor::platform()?->id;

        return $this->lineItemsQuery($from, $to, $vendorId)
            ->with(['order.customer', 'vendor', 'payoutItem.payout'])
            ->orderBy('id')
            ->get()
            ->map(function (ShopOrderItem $item) use ($platformVendorId) {
                $isOwnShop = $platformVendorId && (int) $item->vendor_id === (int) $platformVendorId;
                $order = $item->order;
                $payout = $item->payoutItem?->payout;

                $payoutStatus = 'N/A (own shop)';
                if (! $isOwnShop) {
                    $payoutStatus = match ($payout?->status) {
                        'paid' => 'Paid out',
                        'due' => 'Payout due',
                        'cancelled' => 'Payout cancelled',
                        default => 'Unpaid',
                    };
                }

                return [
                    'order_reference' => $order?->reference,
                    'order_date' => optional($order?->paid_at ?? $order?->created_at)?->format('Y-m-d H:i'),
                    'order_status' => $order?->status,
                    'customer_name' => $order?->customer?->name,
                    'customer_email' => $order?->customer?->email,
                    'vendor_name' => $item->vendor_name ?: ($item->vendor?->name ?? '—'),
                    'sale_type' => $isOwnShop ? 'Own shop' : 'Marketplace seller',
                    'product_name' => $item->name,
                    'options' => $item->options_label,
                    'quantity' => (int) $item->quantity,
                    'unit_price' => round((float) $item->price, 2),
                    'line_gross' => $item->lineTotal(),
                    'commission_rate' => round((float) $item->commission_rate, 2),
                    'commission_amount' => round((float) $item->commission_amount, 2),
                    'vendor_net' => round((float) $item->vendor_net, 2),
                    'fulfilment_status' => $item->fulfilment_status,
                    'payout_status' => $payoutStatus,
                    'payout_reference' => $payout?->reference,
                    'payout_paid_at' => optional($payout?->paid_at)?->format('Y-m-d H:i'),
                ];
            });
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function vendorBreakdown(?CarbonInterface $from = null, ?CarbonInterface $to = null, ?int $vendorId = null): Collection
    {
        return $this->detailRows($from, $to, $vendorId)
            ->groupBy('vendor_name')
            ->map(function (Collection $rows, string $vendorName) {
                $isOwn = $rows->first()['sale_type'] === 'Own shop';

                return [
                    'vendor_name' => $vendorName,
                    'sale_type' => $isOwn ? 'Own shop' : 'Marketplace seller',
                    'lines' => $rows->count(),
                    'gross' => round((float) $rows->sum('line_gross'), 2),
                    'commission' => round((float) $rows->sum('commission_amount'), 2),
                    'vendor_net' => round((float) $rows->sum('vendor_net'), 2),
                    'unpaid_net' => round(
                        (float) $rows
                            ->filter(fn (array $row) => $row['payout_status'] === 'Unpaid')
                            ->sum('vendor_net'),
                        2
                    ),
                ];
            })
            ->sortBy('vendor_name')
            ->values();
    }

    /**
     * Order-level rows for the filtered period / vendor.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function orderRows(?CarbonInterface $from = null, ?CarbonInterface $to = null, ?int $vendorId = null): Collection
    {
        $orderIds = $this->lineItemsQuery($from, $to, $vendorId)
            ->pluck('shop_order_id')
            ->unique()
            ->values();

        if ($orderIds->isEmpty()) {
            return collect();
        }

        $lineStats = $this->lineItemsQuery($from, $to, $vendorId)
            ->with('vendor')
            ->get()
            ->groupBy('shop_order_id');

        return ShopOrder::query()
            ->with('customer')
            ->whereIn('id', $orderIds)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (ShopOrder $order) use ($lineStats, $vendorId) {
                $lines = $lineStats->get($order->id, collect());
                $vendors = $lines
                    ->map(fn (ShopOrderItem $item) => $item->vendor_name ?: ($item->vendor?->name ?? null))
                    ->filter()
                    ->unique()
                    ->values()
                    ->implode(', ');

                $products = $lines
                    ->map(fn (ShopOrderItem $item) => $item->name.' × '.$item->quantity)
                    ->implode('; ');

                return [
                    'order_reference' => $order->reference,
                    'order_date' => optional($order->paid_at ?? $order->created_at)?->format('Y-m-d H:i'),
                    'paid_at' => optional($order->paid_at)?->format('Y-m-d H:i'),
                    'order_status' => $order->status,
                    'fulfilment' => $order->fulfilment,
                    'payment_method' => $order->payment_method,
                    'customer_name' => $order->customer?->name,
                    'customer_email' => $order->customer?->email,
                    'customer_phone' => $order->customer?->phone,
                    'vendors' => $vendors !== '' ? $vendors : '—',
                    'products' => $products,
                    'line_count' => $lines->count(),
                    'items_qty' => (int) $lines->sum('quantity'),
                    'line_gross' => round((float) $lines->sum(fn (ShopOrderItem $i) => $i->lineTotal()), 2),
                    'commission_amount' => round((float) $lines->sum('commission_amount'), 2),
                    'vendor_net' => round((float) $lines->sum('vendor_net'), 2),
                    'order_subtotal' => round((float) $order->subtotal, 2),
                    'shipping_amount' => $vendorId ? null : round((float) $order->shipping_amount, 2),
                    'discount_amount' => $vendorId ? null : round((float) $order->discount_amount, 2),
                    'order_total' => $vendorId ? null : round((float) $order->total, 2),
                    'coupon_code' => $order->coupon_code,
                    'notes' => $order->notes,
                    'stripe_payment_intent' => $order->stripe_payment_intent,
                ];
            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<ShopOrderItem>
     */
    protected function lineItemsQuery(?CarbonInterface $from = null, ?CarbonInterface $to = null, ?int $vendorId = null)
    {
        return ShopOrderItem::query()
            ->when($vendorId, fn ($q) => $q->where('vendor_id', $vendorId))
            ->whereHas('order', function ($query) use ($from, $to) {
                $query->whereIn('status', self::paidStatuses());

                if ($from) {
                    $query->where(function ($q) use ($from) {
                        $q->whereDate('paid_at', '>=', $from)
                            ->orWhere(function ($q2) use ($from) {
                                $q2->whereNull('paid_at')->whereDate('created_at', '>=', $from);
                            });
                    });
                }

                if ($to) {
                    $query->where(function ($q) use ($to) {
                        $q->whereDate('paid_at', '<=', $to)
                            ->orWhere(function ($q2) use ($to) {
                                $q2->whereNull('paid_at')->whereDate('created_at', '<=', $to);
                            });
                    });
                }
            })
            ->with('payoutItem');
    }

    public function parseDate(?string $value, bool $endOfDay = false): ?Carbon
    {
        if (! filled($value)) {
            return null;
        }

        $date = Carbon::parse($value)->startOfDay();

        return $endOfDay ? $date->endOfDay() : $date;
    }
}
