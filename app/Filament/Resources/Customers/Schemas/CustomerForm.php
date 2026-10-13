<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('address_line_1')
                    ->default(null),
                TextInput::make('address_line_2')
                    ->default(null),
                TextInput::make('city')
                    ->default(null),
                TextInput::make('postcode')
                    ->default(null),
            ]);
    }
}
