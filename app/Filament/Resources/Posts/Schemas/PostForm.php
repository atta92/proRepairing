<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('excerpt')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Cover image')
                    ->image()
                    ->directory('posts')
                    ->disk('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                TextInput::make('author_name')
                    ->label('Author')
                    ->default('Repairing Team')
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('published_at')
                    ->label('Publish date')
                    ->seconds(false)
                    ->default(now()),
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),
            ]);
    }
}
