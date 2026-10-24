<?php

namespace App\Filament\Seller\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SellerOrdersTable
{
    public static function configure(Table $table): Table
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('vendor_lines')
                    ->label('Your lines')
                    ->state(fn ($record) => $record->items->where('vendor_id', $vendorId)->sum('quantity')),
                TextColumn::make('vendor_net')
                    ->label('Your net')
                    ->state(fn ($record) => '£'.number_format((float) $record->items->where('vendor_id', $vendorId)->sum('vendor_net'), 2)),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'collected' => 'Collected',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('View'),
            ]);
    }
}
