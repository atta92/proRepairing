<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CouponService
{
    private const SESSION_KEY = 'shop_coupon_code';

    public function currentCode(): ?string
    {
        $code = Session::get(self::SESSION_KEY);

        return is_string($code) && $code !== '' ? strtoupper($code) : null;
    }

    public function current(): ?Coupon
    {
        $code = $this->currentCode();

        if (! $code) {
            return null;
        }

        return Coupon::query()->where('code', $code)->first();
    }

    public function apply(string $code, float $subtotal): Coupon
    {
        $coupon = Coupon::query()
            ->where('code', strtoupper(trim($code)))
            ->first();

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => 'That coupon code was not found.',
            ]);
        }

        if (! $coupon->isCurrentlyValid()) {
            throw ValidationException::withMessages([
                'coupon_code' => 'That coupon is not active or has expired.',
            ]);
        }

        if ($coupon->min_subtotal !== null && $subtotal < (float) $coupon->min_subtotal) {
            throw ValidationException::withMessages([
                'coupon_code' => 'Minimum spend for this coupon is £' . number_format((float) $coupon->min_subtotal, 2) . '.',
            ]);
        }

        $discount = $coupon->discountForSubtotal($subtotal);

        if ($discount <= 0) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon cannot be applied to your bag.',
            ]);
        }

        Session::put(self::SESSION_KEY, $coupon->code);

        return $coupon;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * @param  array{subtotal: float, shipping_amount: float, total: float, count: int}  $totals
     * @return array{subtotal: float, shipping_amount: float, discount_amount: float, total: float, count: int, coupon: ?array}
     */
    public function withDiscount(array $totals): array
    {
        $coupon = $this->current();
        $discount = 0.0;
        $payload = null;

        if ($coupon && $coupon->isCurrentlyValid()) {
            $discount = $coupon->discountForSubtotal((float) $totals['subtotal']);

            if ($discount > 0) {
                $payload = [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'label' => $coupon->typeLabel(),
                ];
            } else {
                $this->clear();
                $coupon = null;
            }
        } elseif ($this->currentCode()) {
            $this->clear();
        }

        $total = round(max(0, (float) $totals['subtotal'] + (float) $totals['shipping_amount'] - $discount), 2);

        return [
            'subtotal' => (float) $totals['subtotal'],
            'shipping_amount' => (float) $totals['shipping_amount'],
            'discount_amount' => $discount,
            'total' => $total,
            'count' => (int) $totals['count'],
            'coupon' => $payload,
        ];
    }

    public function markUsed(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}
