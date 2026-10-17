<?php

namespace App\Filament\Resources\Accessories\Schemas;

use App\Models\Accessory;
use App\Models\AccessoryVariant;
use App\Support\FilamentImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                TextInput::make('page_title')
                    ->label('Page title')
                    ->maxLength(255)
                    ->helperText('Optional heading shown above the description on the product page.'),
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
                    ->columnSpanFull()
                    ->helperText('Shown at the top of the product page.'),
                Section::make('Base price (no variants)')
                    ->description('Used when this product has no variants. For phones/laptops, add variants below instead.')
                    ->columns(3)
                    ->schema([
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
                    ]),
                FilamentImageUpload::product(
                    FileUpload::make('gallery_paths')
                        ->label('Product photos')
                        ->directory('accessories')
                        ->columnSpanFull(),
                    multiple: true
                ),
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
                Section::make('Variants')
                    ->description('For phones and laptops: storage, colour, condition, unlock, RAM/CPU. Each row is a buyable option with its own price and stock.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => collect([
                                $state['storage'] ?? null,
                                $state['color'] ?? null,
                                $state['condition'] ?? null,
                                $state['unlock_status'] ?? null,
                                $state['ram'] ?? null,
                                $state['cpu'] ?? null,
                            ])->filter()->implode(' · ') ?: 'New variant')
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(100),
                                TextInput::make('storage')
                                    ->placeholder('128 GB')
                                    ->maxLength(50),
                                TextInput::make('color')
                                    ->placeholder('Midnight')
                                    ->maxLength(50),
                                Select::make('condition')
                                    ->options(AccessoryVariant::CONDITIONS)
                                    ->searchable(),
                                Select::make('unlock_status')
                                    ->label('Unlock')
                                    ->options(AccessoryVariant::UNLOCK_STATUSES),
                                TextInput::make('ram')
                                    ->label('RAM')
                                    ->placeholder('8 GB')
                                    ->maxLength(50),
                                TextInput::make('cpu')
                                    ->label('CPU')
                                    ->placeholder('M2 / i7-1260P')
                                    ->maxLength(100),
                                TextInput::make('screen_size')
                                    ->label('Screen')
                                    ->placeholder('13.3"')
                                    ->maxLength(50),
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('£')
                                    ->step(0.01)
                                    ->minValue(0),
                                TextInput::make('compare_at_price')
                                    ->label('Compare-at')
                                    ->numeric()
                                    ->prefix('£')
                                    ->step(0.01)
                                    ->minValue(0),
                                TextInput::make('stock')
                                    ->numeric()
                                    ->minValue(0)
                                    ->helperText('Empty = unlimited'),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                                FilamentImageUpload::product(
                                    FileUpload::make('image_path')
                                        ->label('Variant image')
                                        ->directory('accessories/variants')
                                        ->columnSpanFull()
                                ),
                            ])
                            ->columns(4)
                            ->columnSpanFull()
                            ->addActionLabel('Add variant'),
                    ]),
            ]);
    }
}
