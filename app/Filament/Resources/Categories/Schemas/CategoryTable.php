<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Schemas;

use App\Domains\Catalog\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class CategoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('category.fields.name'))
                    ->getStateUsing(static fn (Category $record): string => $record->name)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label(__('category.fields.parent'))
                    ->getStateUsing(static fn (Category $record): string => $record->parent->name ?? '—')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('slug')
                    ->label(__('category.fields.slug'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('products_count')
                    ->label(__('category.fields.products_count'))
                    ->counts('products')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('category.fields.is_active'))
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label(__('category.fields.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make()
                    ->label(__('common.actions.edit')),
                DeleteAction::make()
                    ->label(__('common.actions.delete'))
                    ->before(static function (DeleteAction $action, Category $record): void {
                        if ($record->products()->exists() || $record->children()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title(__('category.notifications.cannot_delete_in_use'))
                                ->send();

                            $action->cancel();
                        }
                    }),
            ]);
    }
}
