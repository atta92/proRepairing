<?php

namespace App\Filament\Resources\DeviceRepairs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeviceRepairForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('device_id')
                    ->relationship('device', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('repair_id')
                    ->relationship('repair', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('£')
                    ->step(0.01),
                TextInput::make('compare_price')
                    ->numeric()
                    ->prefix('£')
                    ->step(0.01),
                TextInput::make('turnaround')
                    ->placeholder('Same day'),
                TextInput::make('warranty_months')
                    ->numeric()
                    ->default(12)
                    ->required(),
                Toggle::make('is_diagnostic')
                    ->default(false)
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
