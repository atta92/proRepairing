<?php

namespace App\Filament\Seller\Resources\Orders\Schemas;

use App\Models\ShopOrderItem;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SellerOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        return $schema
            ->components([
                Section::make('Order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')->disabled(),
                        TextInput::make('status')->disabled(),
                        Placeholder::make('customer_name')
                            ->label('Customer')
                            ->content(fn ($record) => $record?->customer?->name),
                        Placeholder::make('customer_email')
                            ->label('Email')
                            ->content(fn ($record) => $record?->customer?->email),
                    ]),
                Section::make('Your items')
                    ->schema([
                        Repeater::make('seller_items')
                            ->label('')
                            ->relationship(
                                name: 'items',
                                modifyQueryUsing: fn ($query) => $query->where('vendor_id', $vendorId)
                            )
                            ->schema([
                                TextInput::make('name')->disabled()->columnSpan(2),
                                TextInput::make('quantity')->disabled(),
                                TextInput::make('price')->disabled()->prefix('£'),
                                TextInput::make('vendor_net')->label('Your net')->disabled()->prefix('£'),
                                Select::make('fulfilment_status')
                                    ->options(ShopOrderItem::FULFILMENT_STATUSES)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ]);
    }
}
