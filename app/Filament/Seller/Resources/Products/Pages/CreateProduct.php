<?php

namespace App\Filament\Seller\Resources\Products\Pages;

use App\Filament\Seller\Resources\Products\ProductResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Filament::auth()->user()->vendor->id;
        $data['is_featured'] = false;

        return $data;
    }
}
