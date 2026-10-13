<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state) . '-repair')),
                TextInput::make('page_title')
                    ->label('Page title')
                    ->maxLength(255)
                    ->helperText('Optional heading shown above the description on the device page.'),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('excerpt')
                    ->rows(2)
                    ->columnSpanFull()
                    ->helperText('Short summary used in listings.'),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Shown at the top of the device repair page.'),
                Textarea::make('content')
                    ->rows(5)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->directory('devices')
                    ->disk('public'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
