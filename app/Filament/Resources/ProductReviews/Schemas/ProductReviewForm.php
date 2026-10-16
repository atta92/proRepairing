<?php

namespace App\Filament\Resources\ProductReviews\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('accessory_id')
                    ->label('Product')
                    ->relationship('accessory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('author_name')
                    ->label('Name')
                    ->required()
                    ->maxLength(120),
                TextInput::make('author_email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                Select::make('rating')
                    ->options([
                        5 => '5 stars',
                        4 => '4 stars',
                        3 => '3 stars',
                        2 => '2 stars',
                        1 => '1 star',
                    ])
                    ->required()
                    ->default(5),
                TextInput::make('title')
                    ->maxLength(160)
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Photo (optional)')
                    ->image()
                    ->directory('reviews')
                    ->disk('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                Toggle::make('is_approved')
                    ->label('Approved (visible on site)')
                    ->default(true),
                Toggle::make('is_verified')
                    ->label('Verified purchase')
                    ->helperText('Auto-set when the reviewer email matches a shop order for this product. You can override it here.')
                    ->default(false),
            ]);
    }
}
