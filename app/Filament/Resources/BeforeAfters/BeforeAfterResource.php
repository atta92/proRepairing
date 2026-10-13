<?php

namespace App\Filament\Resources\BeforeAfters;

use App\Filament\Resources\BeforeAfters\Pages\CreateBeforeAfter;
use App\Filament\Resources\BeforeAfters\Pages\EditBeforeAfter;
use App\Filament\Resources\BeforeAfters\Pages\ListBeforeAfters;
use App\Filament\Resources\BeforeAfters\Schemas\BeforeAfterForm;
use App\Filament\Resources\BeforeAfters\Tables\BeforeAftersTable;
use App\Models\BeforeAfter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BeforeAfterResource extends Resource
{
    protected static ?string $model = BeforeAfter::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Before & After';

    protected static ?string $modelLabel = 'Before & After';

    protected static ?string $pluralModelLabel = 'Before & After';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return BeforeAfterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeforeAftersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBeforeAfters::route('/'),
            'create' => CreateBeforeAfter::route('/create'),
            'edit' => EditBeforeAfter::route('/{record}/edit'),
        ];
    }
}
