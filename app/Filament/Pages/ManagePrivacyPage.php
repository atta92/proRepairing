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
class ManagePrivacyPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Privacy policy';

    protected static ?string $title = 'Privacy policy';

    protected static ?string $slug = 'privacy-page';

    protected static ?int $navigationSort = 22;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->form->fill([
            'privacy_title' => $settings->privacy_title,
            'privacy_body' => $settings->privacy_body,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Privacy policy')
                    ->description('Content shown on the public Privacy policy page.')
                    ->schema([
                        TextInput::make('privacy_title')
                            ->label('Page title')
                            ->maxLength(255)
                            ->required(),
                        RichEditor::make('privacy_body')
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
                        ->label('Save privacy policy')
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
            'privacy_title' => $data['privacy_title'] ?? null,
            'privacy_body' => $data['privacy_body'] ?? null,
        ]);
        $settings->save();

        Notification::make()
            ->title('Privacy policy saved')
            ->success()
            ->send();
    }
}
