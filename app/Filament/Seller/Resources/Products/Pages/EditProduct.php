<?php

namespace App\Filament\Seller\Resources\Products\Pages;

use App\Filament\Seller\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['vendor_id'] = Filament::auth()->user()->vendor->id;

        return $data;
    }
}
