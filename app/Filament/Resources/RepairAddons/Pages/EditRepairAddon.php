<?php

namespace App\Filament\Resources\RepairAddons\Pages;

use App\Filament\Resources\RepairAddons\RepairAddonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRepairAddon extends EditRecord
{
    protected static string $resource = RepairAddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
