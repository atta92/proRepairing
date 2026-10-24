<?php

namespace App\Filament\Seller\Resources\Orders;

use App\Filament\Seller\Resources\Orders\Pages\EditSellerOrder;
use App\Filament\Seller\Resources\Orders\Pages\ListSellerOrders;
use App\Filament\Seller\Resources\Orders\Schemas\SellerOrderForm;
use App\Filament\Seller\Resources\Orders\Tables\SellerOrdersTable;
use App\Models\ShopOrder;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SellerOrderResource extends Resource
{
    protected static ?string $model = ShopOrder::class;

    protected static ?string $recordTitleAttribute = 'reference';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'order';

    protected static ?string $pluralModelLabel = 'orders';

    protected static ?string $slug = 'orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        return parent::getEloquentQuery()
            ->whereHas('items', fn (Builder $q) => $q->where('vendor_id', $vendorId))
            ->with(['customer', 'items' => fn ($q) => $q->where('vendor_id', $vendorId)]);
    }

    public static function form(Schema $schema): Schema
    {
        return SellerOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SellerOrdersTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSellerOrders::route('/'),
            'edit' => EditSellerOrder::route('/{record}/edit'),
        ];
    }
}
