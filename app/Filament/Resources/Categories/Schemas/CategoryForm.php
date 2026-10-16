<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Support\FilamentImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('page_title')
                    ->label('Page title')
                    ->maxLength(255)
                    ->helperText('Optional heading shown above the description on the category page.'),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->options([
                        'phone' => 'Phone',
                        'tablet' => 'Tablet',
                        'laptop' => 'Laptop',
                        'watch' => 'Watch',
                    ])
                    ->required(),
                TextInput::make('excerpt')
                    ->maxLength(255)
                    ->helperText('Short summary used in listings.'),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Shown at the top of the category page.'),
                FilamentImageUpload::catalogue(
                    FileUpload::make('image_path')
                        ->label('Image')
                        ->directory('categories')
                ),
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
