<?php

namespace App\Filament\Resources\DeviceRepairs;

use App\Filament\Resources\DeviceRepairs\Pages\CreateDeviceRepair;
use App\Filament\Resources\DeviceRepairs\Pages\EditDeviceRepair;
use App\Filament\Resources\DeviceRepairs\Pages\ListDeviceRepairs;
use App\Filament\Resources\DeviceRepairs\Schemas\DeviceRepairForm;
use App\Filament\Resources\DeviceRepairs\Tables\DeviceRepairsTable;
use App\Models\DeviceRepair;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceRepairResource extends Resource
{
    protected static ?string $model = DeviceRepair::class;

    protected static ?string $navigationLabel = 'Prices / SKUs';

    protected static ?string $modelLabel = 'device repair';

    protected static ?string $pluralModelLabel = 'device repairs';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyPound;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return DeviceRepairForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceRepairsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeviceRepairs::route('/'),
            'create' => CreateDeviceRepair::route('/create'),
            'edit' => EditDeviceRepair::route('/{record}/edit'),
        ];
    }
}
