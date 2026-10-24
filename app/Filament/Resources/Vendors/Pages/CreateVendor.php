<?php

namespace App\Filament\Resources\Vendors\Pages;

use App\Filament\Resources\Vendors\VendorResource;
use App\Models\Vendor;
use Filament\Resources\Pages\CreateRecord;

class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['is_platform'])) {
            Vendor::query()->where('is_platform', true)->update(['is_platform' => false]);
            $data['commission_rate'] = 0;
            $data['status'] = 'approved';
        }

        return $data;
    }
}
