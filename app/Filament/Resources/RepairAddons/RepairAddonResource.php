<?php

namespace App\Filament\Resources\RepairAddons;

use App\Filament\Resources\RepairAddons\Pages\CreateRepairAddon;
use App\Filament\Resources\RepairAddons\Pages\EditRepairAddon;
use App\Filament\Resources\RepairAddons\Pages\ListRepairAddons;
use App\Filament\Resources\RepairAddons\Schemas\RepairAddonForm;
use App\Filament\Resources\RepairAddons\Tables\RepairAddonsTable;
use App\Models\RepairAddon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RepairAddonResource extends Resource
{
    protected static ?string $model = RepairAddon::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'protect-addons';

    protected static ?string $navigationLabel = 'Protect Add-ons';

    protected static ?string $modelLabel = 'protect add-on';

    protected static ?string $pluralModelLabel = 'protect add-ons';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Catalogue';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return RepairAddonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RepairAddonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepairAddons::route('/'),
            'create' => CreateRepairAddon::route('/create'),
            'edit' => EditRepairAddon::route('/{record}/edit'),
        ];
    }
}
