<?php

namespace App\Filament\Resources\ShopOrders;

use App\Filament\Resources\ShopOrders\Pages\EditShopOrder;
use App\Filament\Resources\ShopOrders\Pages\ListShopOrders;
use App\Filament\Resources\ShopOrders\Schemas\ShopOrderForm;
use App\Filament\Resources\ShopOrders\Tables\ShopOrdersTable;
use App\Models\ShopOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShopOrderResource extends Resource
{
    protected static ?string $model = ShopOrder::class;

    protected static ?string $recordTitleAttribute = 'reference';

    protected static ?string $navigationLabel = 'Shop orders';

    protected static ?string $modelLabel = 'Shop order';

    protected static ?string $pluralModelLabel = 'Shop orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|\UnitEnum|null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ShopOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShopOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('items');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShopOrders::route('/'),
            'edit' => EditShopOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
