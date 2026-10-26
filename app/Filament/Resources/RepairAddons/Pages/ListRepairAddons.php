<?php

namespace App\Filament\Resources\RepairAddons\Pages;

use App\Filament\Resources\RepairAddons\RepairAddonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRepairAddons extends ListRecords
{
    protected static string $resource = RepairAddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
