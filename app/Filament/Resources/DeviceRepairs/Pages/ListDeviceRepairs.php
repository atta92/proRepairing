<?php

namespace App\Filament\Resources\DeviceRepairs\Pages;

use App\Filament\Resources\DeviceRepairs\DeviceRepairResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeviceRepairs extends ListRecords
{
    protected static string $resource = DeviceRepairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
