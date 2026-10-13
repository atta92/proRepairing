<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
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
class ManageStripeSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Stripe';

    protected static ?string $title = 'Stripe settings';

    protected static ?string $slug = 'stripe-settings';

    protected static ?int $navigationSort = 90;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();

        $this->form->fill([
            'stripe_publishable_key' => $settings->stripe_publishable_key,
            'stripe_secret_key' => $settings->stripe_secret_key,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stripe API keys')
                    ->description('Used for online card payments on the shop checkout. The secret key is encrypted at rest.')
                    ->schema([
                        TextInput::make('stripe_publishable_key')
                            ->label('Publishable key')
                            ->placeholder('pk_test_...')
                            ->helperText('Starts with pk_test_ or pk_live_')
                            ->maxLength(255),
                        TextInput::make('stripe_secret_key')
                            ->label('Secret key')
                            ->password()
                            ->revealable()
                            ->placeholder('sk_test_...')
                            ->helperText('Starts with sk_test_ or sk_live_. Clear and re-enter to replace the saved secret.')
                            ->maxLength(255)
                            ->dehydrated(fn (?string $state): bool => filled($state)),
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
                        ->label('Save keys')
                        ->submit('save')
                        ->keyBindings(['mod+s']),
                ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = SiteSetting::current();

        $payload = [
            'stripe_publishable_key' => $data['stripe_publishable_key'] ?? null,
        ];

        if (array_key_exists('stripe_secret_key', $data) && filled($data['stripe_secret_key'])) {
            $payload['stripe_secret_key'] = $data['stripe_secret_key'];
        }

        $settings->fill($payload);
        $settings->save();

        Notification::make()
            ->title('Stripe settings saved')
            ->success()
            ->send();
    }
}
