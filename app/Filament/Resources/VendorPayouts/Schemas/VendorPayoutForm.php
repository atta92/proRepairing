<?php

namespace App\Filament\Resources\VendorPayouts\Schemas;

use App\Models\VendorPayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorPayoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_id')
                    ->relationship('vendor', 'name', fn ($query) => $query->where('is_platform', false)->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Creating a payout attaches all unpaid paid-order item nets for this vendor.'),
                TextInput::make('amount')
                    ->numeric()
                    ->prefix('£')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Calculated from selected unpaid items on save.'),
                Select::make('status')
                    ->options(VendorPayout::STATUSES)
                    ->required()
                    ->default('due'),
                TextInput::make('reference')
                    ->maxLength(255),
                DatePicker::make('period_from'),
                DatePicker::make('period_to'),
                DatePicker::make('paid_at'),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
