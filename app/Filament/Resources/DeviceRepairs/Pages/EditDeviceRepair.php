<?php

namespace App\Filament\Resources\DeviceRepairs\Pages;

use App\Filament\Resources\DeviceRepairs\DeviceRepairResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceRepair extends EditRecord
{
    protected static string $resource = DeviceRepairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
