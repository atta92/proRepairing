<?php

namespace App\Filament\Seller\Resources\Orders\Pages;

use App\Filament\Seller\Resources\Orders\SellerOrderResource;
use App\Models\ShopOrderItem;
use App\Services\OrderMailer;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;

class EditSellerOrder extends EditRecord
{
    protected static string $resource = SellerOrderResource::class;

    /**
     * @var array<int, string>
     */
    protected array $previousFulfilmentStatuses = [];

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        $this->previousFulfilmentStatuses = $this->record->items()
            ->when($vendorId, fn ($query) => $query->where('vendor_id', $vendorId))
            ->pluck('fulfilment_status', 'id')
            ->all();
    }

    protected function afterSave(): void
    {
        $vendor = Filament::auth()->user()?->vendor;
        $vendorId = $vendor?->id;

        if (! $vendorId) {
            return;
        }

        $this->record->load(['customer', 'items']);

        $changes = [];

        foreach ($this->record->items->where('vendor_id', $vendorId) as $item) {
            /** @var ShopOrderItem $item */
            $from = $this->previousFulfilmentStatuses[$item->id] ?? null;

            if ($from === null || $from === $item->fulfilment_status) {
                continue;
            }

            $changes[] = [
                'item' => $item,
                'from' => $from,
                'to' => (string) $item->fulfilment_status,
            ];
        }

        if ($changes === []) {
            return;
        }

        app(OrderMailer::class)->shopOrderFulfilmentUpdated($this->record, $changes, $vendor);
    }
}
