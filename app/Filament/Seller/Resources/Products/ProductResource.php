<?php

namespace App\Filament\Seller\Resources\Products;

use App\Filament\Resources\Accessories\Schemas\AccessoryForm;
use App\Filament\Seller\Resources\Products\Pages\CreateProduct;
use App\Filament\Seller\Resources\Products\Pages\EditProduct;
use App\Filament\Seller\Resources\Products\Pages\ListProducts;
use App\Filament\Seller\Resources\Products\Tables\ProductsTable;
use App\Models\Accessory;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Accessory::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $modelLabel = 'product';

    protected static ?string $pluralModelLabel = 'products';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        return parent::getEloquentQuery()
            ->when($vendorId, fn (Builder $q) => $q->where('vendor_id', $vendorId));
    }

    public static function form(Schema $schema): Schema
    {
        return AccessoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return Filament::auth()->user()?->vendor?->isApproved() === true;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::owns($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::owns($record);
    }

    protected static function owns(Model $record): bool
    {
        $vendorId = Filament::auth()->user()?->vendor?->id;

        return $vendorId && (int) $record->vendor_id === (int) $vendorId;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
