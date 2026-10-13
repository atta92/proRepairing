<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
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
class ManageContactSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Contact details';

    protected static ?string $title = 'Contact details';

    protected static ?string $slug = 'contact-settings';

    protected static ?int $navigationSort = 20;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->form->fill([
            'contact_email' => $settings->contact_email,
            'contact_phone' => $settings->contact_phone,
            'contact_hours' => $settings->contact_hours,
            'contact_address' => $settings->contact_address,
            'contact_intro' => $settings->contact_intro,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact information')
                    ->description('Shown in the website footer and on the Contact us page.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('contact_phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('contact_hours')
                            ->label('Opening hours')
                            ->maxLength(255)
                            ->placeholder('Mon–Sun · Walk-ins welcome'),
                        TextInput::make('contact_address')
                            ->label('Address')
                            ->maxLength(255),
                        Textarea::make('contact_intro')
                            ->label('Intro text')
                            ->rows(3)
                            ->helperText('Short paragraph shown on the Contact us page and home contact section.')
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
                        ->label('Save contact details')
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
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_hours' => $data['contact_hours'] ?? null,
            'contact_address' => $data['contact_address'] ?? null,
            'contact_intro' => $data['contact_intro'] ?? null,
        ]);
        $settings->save();

        Notification::make()
            ->title('Contact details saved')
            ->success()
            ->send();
    }
}
