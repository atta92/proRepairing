<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\Vendor;
use App\Support\FilamentImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Shop profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('user_id')
                            ->label('Linked user account')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('status')
                            ->options(Vendor::STATUSES)
                            ->required()
                            ->default('pending'),
                        TextInput::make('commission_rate')
                            ->label('Commission %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->suffix('%')
                            ->default(10)
                            ->helperText('Platform shop is always 0%.'),
                        Toggle::make('is_platform')
                            ->label('Own / platform shop')
                            ->helperText('Only one platform shop. No commission. Always first on the home shop tabs.'),
                        TextInput::make('sort_order')
                            ->label('Home tab priority')
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->default(100)
                            ->helperText('Lower number = higher on the home shop seller tabs (own shop stays first).'),
                        FilamentImageUpload::catalogue(
                            FileUpload::make('logo_path')
                                ->label('Logo')
                                ->directory('vendors/logos')
                        ),
                        Textarea::make('payout_details')
                            ->label('Payout details')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Bank / PayPal notes for manual payouts.'),
                    ]),
                Section::make('Address')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('address_line_1')->maxLength(255),
                        TextInput::make('address_line_2')->maxLength(255),
                        TextInput::make('city')->maxLength(100),
                        TextInput::make('postcode')->maxLength(20),
                        TextInput::make('country')->maxLength(2)->default('GB'),
                    ]),
            ]);
    }
}
