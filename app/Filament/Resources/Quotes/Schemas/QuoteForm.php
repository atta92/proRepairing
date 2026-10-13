<?php

namespace App\Filament\Resources\Quotes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('device_name')
                    ->label('Device (free text)'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('device_id')
                    ->relationship('device', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->directory('quotes')
                    ->disk('public'),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'quoted' => 'Quoted',
                        'converted' => 'Converted',
                        'closed' => 'Closed',
                    ])
                    ->required()
                    ->default('new'),
            ]);
    }
}
