<?php

namespace App\Filament\Resources\BeforeAfters\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BeforeAfterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Product / repair title')
                    ->maxLength(255)
                    ->helperText('Optional — leave blank to hide the title on the landing page.')
                    ->columnSpanFull(),
                Section::make('Images')
                    ->description('Upload the same product: one photo before the repair, one after.')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('before_image_path')
                            ->label('Before')
                            ->image()
                            ->directory('before-after/before')
                            ->disk('public')
                            ->imageEditor()
                            ->required()
                            ->helperText('Shown in the Before window'),
                        FileUpload::make('after_image_path')
                            ->label('After')
                            ->image()
                            ->directory('before-after/after')
                            ->disk('public')
                            ->imageEditor()
                            ->required()
                            ->helperText('Shown in the After window'),
                    ]),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Show on landing page')
                    ->default(true),
            ]);
    }
}
