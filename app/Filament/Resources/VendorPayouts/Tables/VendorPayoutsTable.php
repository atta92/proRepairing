<?php

namespace App\Filament\Resources\VendorPayouts\Tables;

use App\Models\VendorPayout;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VendorPayoutsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('vendor.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'due' => 'warning',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Lines'),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(VendorPayout::STATUSES),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
