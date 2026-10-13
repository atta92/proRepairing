<?php

namespace App\Filament\Resources\BeforeAfters\Pages;

use App\Filament\Resources\BeforeAfters\BeforeAfterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeforeAfters extends ListRecords
{
    protected static string $resource = BeforeAfterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
