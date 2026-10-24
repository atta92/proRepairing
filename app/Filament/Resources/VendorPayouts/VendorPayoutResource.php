<?php

namespace App\Filament\Resources\VendorPayouts;

use App\Filament\Resources\VendorPayouts\Pages\CreateVendorPayout;
use App\Filament\Resources\VendorPayouts\Pages\EditVendorPayout;
use App\Filament\Resources\VendorPayouts\Pages\ListVendorPayouts;
use App\Filament\Resources\VendorPayouts\Schemas\VendorPayoutForm;
use App\Filament\Resources\VendorPayouts\Tables\VendorPayoutsTable;
use App\Models\VendorPayout;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorPayoutResource extends Resource
{
    protected static ?string $model = VendorPayout::class;

    protected static ?string $navigationLabel = 'Vendor payouts';

    protected static ?string $modelLabel = 'vendor payout';

    protected static ?string $pluralModelLabel = 'vendor payouts';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return VendorPayoutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorPayoutsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorPayouts::route('/'),
            'create' => CreateVendorPayout::route('/create'),
            'edit' => EditVendorPayout::route('/{record}/edit'),
        ];
    }
}
