<?php

namespace App\Services;

use App\Models\Vendor;

class VendorCommission
{
    /**
     * Allocate an order-level discount across line totals proportionally,
     * then compute commission/net from the discounted line total.
     *
     * @param  list<array{line_total: float, vendor: ?Vendor}>  $lines
     * @return list<array{gross: float, discounted: float, commission_rate: float, commission_amount: float, vendor_net: float}>
     */
    public static function allocate(array $lines, float $discountAmount): array
    {
        $subtotal = round(array_sum(array_column($lines, 'line_total')), 2);
        $discountAmount = max(0, round($discountAmount, 2));
        $remainingDiscount = $discountAmount;
        $count = count($lines);
        $result = [];

        foreach ($lines as $index => $line) {
            $gross = round((float) $line['line_total'], 2);
            $share = 0.0;

            if ($subtotal > 0 && $discountAmount > 0) {
                if ($index === $count - 1) {
                    $share = round($remainingDiscount, 2);
                } else {
                    $share = round($discountAmount * ($gross / $subtotal), 2);
                    $remainingDiscount = round($remainingDiscount - $share, 2);
                }
            }

            $discounted = max(0, round($gross - $share, 2));
            /** @var Vendor|null $vendor */
            $vendor = $line['vendor'] ?? null;
            $rate = $vendor ? $vendor->effectiveCommissionRate() : 0.0;
            $commission = round($discounted * ($rate / 100), 2);
            $net = round($discounted - $commission, 2);

            $result[] = [
                'gross' => $gross,
                'discounted' => $discounted,
                'commission_rate' => $rate,
                'commission_amount' => $commission,
                'vendor_net' => $net,
            ];
        }

        return $result;
    }
}
