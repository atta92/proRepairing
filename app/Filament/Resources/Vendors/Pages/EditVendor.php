<?php

namespace App\Filament\Resources\Vendors\Pages;

use App\Filament\Resources\Vendors\VendorResource;
use App\Models\Vendor;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendor extends EditRecord
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn (Vendor $record): bool => $record->is_platform),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['is_platform'])) {
            Vendor::query()
                ->where('is_platform', true)
                ->where('id', '!=', $this->record->id)
                ->update(['is_platform' => false]);
            $data['commission_rate'] = 0;
            $data['status'] = 'approved';
        }

        return $data;
    }
}
