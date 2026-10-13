<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Models\Coupon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->helperText('Customers enter this at checkout (saved uppercase).')
                    ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : strtoupper(trim($state))),
                Select::make('type')
                    ->options([
                        Coupon::TYPE_PERCENT => 'Percent off',
                        Coupon::TYPE_FIXED => 'Fixed amount off',
                    ])
                    ->required()
                    ->live()
                    ->default(Coupon::TYPE_PERCENT),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01)
                    ->prefix(fn (Get $get): string => $get('type') === Coupon::TYPE_FIXED ? '£' : '')
                    ->suffix(fn (Get $get): ?string => $get('type') === Coupon::TYPE_PERCENT ? '%' : null)
                    ->helperText('Percent: 1–100. Fixed: amount in GBP.'),
                TextInput::make('min_subtotal')
                    ->label('Minimum subtotal')
                    ->numeric()
                    ->prefix('£')
                    ->minValue(0)
                    ->step(0.01)
                    ->helperText('Optional — bag subtotal must reach this before the coupon applies.'),
                TextInput::make('max_uses')
                    ->label('Maximum uses')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Leave empty for unlimited uses.'),
                TextInput::make('used_count')
                    ->label('Times used')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                DateTimePicker::make('starts_at')
                    ->label('Starts at')
                    ->seconds(false),
                DateTimePicker::make('ends_at')
                    ->label('Ends at')
                    ->seconds(false),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Textarea::make('description')
                    ->rows(2)
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
