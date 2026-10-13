<?php

namespace App\Filament\Resources\DeviceRepairs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeviceRepairsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('repair.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('turnaround')
                    ->toggleable(),
                TextColumn::make('warranty_months')
                    ->label('Warranty')
                    ->suffix(' mo')
                    ->sortable(),
                IconColumn::make('is_diagnostic')
                    ->boolean()
                    ->label('Diagnostic'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
