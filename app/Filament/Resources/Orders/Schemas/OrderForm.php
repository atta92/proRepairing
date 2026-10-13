<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('device_id')
                    ->relationship('device', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('fulfilment')
                    ->options([
                        'walk_in' => 'Walk-in',
                        'mail_in' => 'Mail-in',
                    ])
                    ->required(),
                DatePicker::make('scheduled_date'),
                TextInput::make('scheduled_time')
                    ->placeholder('16:30'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('£'),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->prefix('£')
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('£'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'awaiting_device' => 'Awaiting device',
                        'in_repair' => 'In repair',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_fix' => 'No fix',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
