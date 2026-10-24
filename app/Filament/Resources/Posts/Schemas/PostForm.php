<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Services\WordDocumentImporter;
use App\Support\FilamentImageUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Import from Word')
                    ->description('Upload a .docx Word file to fill the blog body automatically. You can still edit everything after import.')
                    ->schema([
                        FileUpload::make('word_import')
                            ->label('Word file')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/msword',
                                'application/octet-stream',
                            ])
                            ->rules(['file', 'mimes:doc,docx'])
                            ->helperText('Supports .docx (recommended). The first heading can also fill the title if it is empty.')
                            ->disk('local')
                            ->directory('word-imports')
                            ->visibility('private')
                            ->maxSize(10240)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                                if (blank($state)) {
                                    return;
                                }

                                $file = is_array($state) ? ($state[0] ?? null) : $state;
                                if (! $file instanceof TemporaryUploadedFile && ! is_string($file)) {
                                    return;
                                }

                                try {
                                    $imported = app(WordDocumentImporter::class)->import($file);

                                    $set('body', $imported['html']);

                                    if (blank($get('title')) && filled($imported['title'])) {
                                        $set('title', $imported['title']);
                                        $set('slug', Str::slug($imported['title']));
                                    }

                                    if (blank($get('excerpt')) && filled($imported['plain'])) {
                                        $set(
                                            'excerpt',
                                            app(WordDocumentImporter::class)->excerptFromPlain($imported['plain'])
                                        );
                                    }

                                    Notification::make()
                                        ->title('Word content imported')
                                        ->body('The blog description/body has been filled from your Word file.')
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Could not read Word file')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),
                    ])
                    ->columnSpanFull()
                    ->collapsed(false),
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
                    ->label('Description / body')
                    ->required()
                    ->columnSpanFull(),
                FilamentImageUpload::landscape(
                    FileUpload::make('image_path')
                        ->label('Cover image')
                        ->directory('posts')
                        ->columnSpanFull()
                ),
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
