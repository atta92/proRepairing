<?php

namespace App\Filament\Resources\VendorPayouts\Pages;

use App\Filament\Resources\VendorPayouts\VendorPayoutResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorPayout extends EditRecord
{
    protected static string $resource = VendorPayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? '') === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        return $data;
    }
}
