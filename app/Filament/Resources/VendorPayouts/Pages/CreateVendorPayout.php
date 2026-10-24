<?php

namespace App\Filament\Resources\VendorPayouts\Pages;

use App\Filament\Resources\VendorPayouts\VendorPayoutResource;
use App\Models\ShopOrderItem;
use App\Models\VendorPayoutItem;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateVendorPayout extends CreateRecord
{
    protected static string $resource = VendorPayoutResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $vendorId = (int) $data['vendor_id'];

            $items = ShopOrderItem::query()
                ->where('vendor_id', $vendorId)
                ->whereDoesntHave('payoutItem')
                ->whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'processing', 'shipped', 'collected']))
                ->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'vendor_id' => 'No unpaid order lines for this vendor.',
                ]);
            }

            $amount = round((float) $items->sum('vendor_net'), 2);

            $payout = static::getModel()::query()->create([
                'vendor_id' => $vendorId,
                'amount' => $amount,
                'currency' => 'GBP',
                'period_from' => $data['period_from'] ?? null,
                'period_to' => $data['period_to'] ?? null,
                'status' => $data['status'] ?? 'due',
                'paid_at' => ($data['status'] ?? '') === 'paid' ? ($data['paid_at'] ?? now()) : ($data['paid_at'] ?? null),
                'reference' => $data['reference'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                VendorPayoutItem::query()->create([
                    'vendor_payout_id' => $payout->id,
                    'shop_order_item_id' => $item->id,
                    'gross' => $item->lineTotal(),
                    'commission' => $item->commission_amount,
                    'net' => $item->vendor_net,
                ]);
            }

            return $payout;
        });
    }
}
