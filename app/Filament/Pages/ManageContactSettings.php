<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'Site settings';

    protected static ?string $slug = 'site-settings';

    protected static ?int $navigationSort = 20;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->form->fill([
            'site_name' => $settings->site_name,
            'site_logo_path' => $settings->site_logo_path,
            'site_description' => $settings->site_description,
            'contact_email' => $settings->contact_email,
            'contact_phone' => $settings->contact_phone,
            'contact_hours' => $settings->contact_hours,
            'contact_address' => $settings->contact_address,
            'contact_intro' => $settings->contact_intro,
            'social_facebook' => $settings->social_facebook,
            'social_instagram' => $settings->social_instagram,
            'social_twitter' => $settings->social_twitter,
            'social_youtube' => $settings->social_youtube,
            'social_linkedin' => $settings->social_linkedin,
            'social_tiktok' => $settings->social_tiktok,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding')
                    ->description('Site name, logo and short description shown in the header and footer.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('site_logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('branding')
                            ->disk('public')
                            ->imageEditor()
                            ->helperText('Shown in the header and footer. Prefer a transparent PNG or SVG.'),
                        Textarea::make('site_description')
                            ->label('Site description')
                            ->rows(3)
                            ->helperText('Shown in the footer and used as a default SEO description fallback.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Contact information')
                    ->description('Shown in the website footer, contact page and home contact section.')
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
                Section::make('Social accounts')
                    ->description('Leave blank to hide a network. Full profile URLs work best.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/...'),
                        TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/...'),
                        TextInput::make('social_twitter')
                            ->label('X / Twitter')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://x.com/...'),
                        TextInput::make('social_youtube')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/...'),
                        TextInput::make('social_linkedin')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://linkedin.com/...'),
                        TextInput::make('social_tiktok')
                            ->label('TikTok')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@...'),
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
                        ->label('Save site settings')
                        ->submit('save')
                        ->keyBindings(['mod+s']),
                ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = SiteSetting::current();

        $logo = $data['site_logo_path'] ?? null;
        if (is_array($logo)) {
            $logo = $logo[0] ?? null;
        }

        $settings->fill([
            'site_name' => $data['site_name'] ?? null,
            'site_logo_path' => $logo,
            'site_description' => $data['site_description'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_hours' => $data['contact_hours'] ?? null,
            'contact_address' => $data['contact_address'] ?? null,
            'contact_intro' => $data['contact_intro'] ?? null,
            'social_facebook' => $data['social_facebook'] ?? null,
            'social_instagram' => $data['social_instagram'] ?? null,
            'social_twitter' => $data['social_twitter'] ?? null,
            'social_youtube' => $data['social_youtube'] ?? null,
            'social_linkedin' => $data['social_linkedin'] ?? null,
            'social_tiktok' => $data['social_tiktok'] ?? null,
        ]);
        $settings->save();

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
