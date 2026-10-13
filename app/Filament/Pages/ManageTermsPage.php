<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageTermsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Terms & conditions';

    protected static ?string $title = 'Terms & conditions';

    protected static ?string $slug = 'terms-page';

    protected static ?int $navigationSort = 23;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->form->fill([
            'terms_title' => $settings->terms_title,
            'terms_body' => $settings->terms_body,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Terms & conditions')
                    ->description('Content shown on the public Terms & conditions page.')
                    ->schema([
                        TextInput::make('terms_title')
                            ->label('Page title')
                            ->maxLength(255)
                            ->required(),
                        RichEditor::make('terms_body')
                            ->label('Content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make([
                    Action::make('save')
                        ->label('Save terms')
                        ->submit('save')
                        ->keyBindings(['mod+s']),
                ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = SiteSetting::current();

        $settings->fill([
            'terms_title' => $data['terms_title'] ?? null,
            'terms_body' => $data['terms_body'] ?? null,
        ]);
        $settings->save();

        Notification::make()
            ->title('Terms & conditions saved')
            ->success()
            ->send();
    }
}
