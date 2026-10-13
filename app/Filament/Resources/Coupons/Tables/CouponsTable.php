<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === Coupon::TYPE_PERCENT ? 'Percent' : 'Fixed'),
                TextColumn::make('value')
                    ->label('Discount')
                    ->formatStateUsing(function ($state, Coupon $record): string {
                        return $record->type === Coupon::TYPE_PERCENT
                            ? rtrim(rtrim(number_format((float) $state, 2), '0'), '.') . '%'
                            : '£' . number_format((float) $state, 2);
                    }),
                TextColumn::make('min_subtotal')
                    ->label('Min spend')
                    ->money('GBP')
                    ->placeholder('—'),
                TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(function ($state, Coupon $record): string {
                        $max = $record->max_uses;

                        return $max === null ? (string) $state : $state . ' / ' . $max;
                    }),
                TextColumn::make('ends_at')
                    ->label('Expires')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Never'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        Coupon::TYPE_PERCENT => 'Percent',
                        Coupon::TYPE_FIXED => 'Fixed',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
