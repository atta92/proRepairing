<?php

namespace App\Filament\Resources\BeforeAfters\Pages;

use App\Filament\Resources\BeforeAfters\BeforeAfterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeforeAfter extends EditRecord
{
    protected static string $resource = BeforeAfterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
