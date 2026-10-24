<?php

namespace App\Filament\Resources\ShopOrders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShopOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Lines'),
                TextColumn::make('fulfilment')
                    ->badge(),
                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge(),
                TextColumn::make('total')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('commission')
                    ->label('Commission')
                    ->state(fn ($record) => (float) $record->items->sum('commission_amount'))
                    ->money('GBP')
                    ->sortable(false),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending payment',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'collected' => 'Collected',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
