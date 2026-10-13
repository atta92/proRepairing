<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('device.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fulfilment')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'walk_in' => 'Walk-in',
                        'mail_in' => 'Mail-in',
                        default => $state,
                    }),
                TextColumn::make('scheduled_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'awaiting_device' => 'warning',
                        'in_repair' => 'info',
                        'ready' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'no_fix' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'awaiting_device' => 'Awaiting device',
                        'in_repair' => 'In repair',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_fix' => 'No fix',
                    ]),
                SelectFilter::make('fulfilment')
                    ->options([
                        'walk_in' => 'Walk-in',
                        'mail_in' => 'Mail-in',
                    ]),
            ])
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
