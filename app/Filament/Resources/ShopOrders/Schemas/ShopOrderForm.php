<?php

namespace App\Filament\Resources\ShopOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShopOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')
                            ->disabled(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending payment',
                                'paid' => 'Paid',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'collected' => 'Collected',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Select::make('fulfilment')
                            ->options([
                                'collection' => 'Collection',
                                'delivery' => 'Delivery',
                            ])
                            ->disabled(),
                        Select::make('payment_method')
                            ->options([
                                'card' => 'Card (Stripe)',
                                'store' => 'Pay in store',
                            ])
                            ->disabled(),
                        TextInput::make('stripe_session_id')
                            ->label('Stripe session')
                            ->disabled(),
                        TextInput::make('stripe_payment_intent')
                            ->label('Stripe payment intent')
                            ->disabled(),
                        TextInput::make('subtotal')
                            ->prefix('£')
                            ->disabled(),
                        TextInput::make('shipping_amount')
                            ->prefix('£')
                            ->disabled(),
                        TextInput::make('discount_amount')
                            ->prefix('£')
                            ->disabled(),
                        TextInput::make('coupon_code')
                            ->disabled(),
                        TextInput::make('total')
                            ->prefix('£')
                            ->disabled(),
                        DateTimePicker::make('paid_at')
                            ->seconds(false),
                        Textarea::make('notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Line items & commission')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')
                                    ->disabled()
                                    ->columnSpan(2),
                                TextInput::make('vendor_name')
                                    ->label('Vendor')
                                    ->disabled(),
                                TextInput::make('quantity')
                                    ->disabled(),
                                TextInput::make('price')
                                    ->prefix('£')
                                    ->disabled(),
                                TextInput::make('commission_rate')
                                    ->label('Rate %')
                                    ->disabled()
                                    ->suffix('%'),
                                TextInput::make('commission_amount')
                                    ->label('Commission')
                                    ->prefix('£')
                                    ->disabled(),
                                TextInput::make('vendor_net')
                                    ->label('Vendor net')
                                    ->prefix('£')
                                    ->disabled(),
                            ])
                            ->columns(4)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ]);
    }
}
