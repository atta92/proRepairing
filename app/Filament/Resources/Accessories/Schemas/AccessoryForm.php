<?php

namespace App\Filament\Resources\Accessories\Schemas;

use App\Models\Accessory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AccessoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->options(Accessory::TYPES)
                    ->required()
                    ->default('accessories')
                    ->searchable(),
                Select::make('category')
                    ->options(Accessory::CATEGORIES)
                    ->required()
                    ->searchable(),
                Textarea::make('excerpt')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(6)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('£')
                    ->step(0.01)
                    ->minValue(0),
                TextInput::make('compare_at_price')
                    ->label('Compare-at price')
                    ->numeric()
                    ->prefix('£')
                    ->step(0.01)
                    ->minValue(0)
                    ->helperText('Optional original price shown struck through.'),
                TextInput::make('stock')
                    ->numeric()
                    ->minValue(0)
                    ->helperText('Leave empty for unlimited stock.'),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->directory('accessories')
                    ->disk('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_featured')
                    ->label('Featured on homepage')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
