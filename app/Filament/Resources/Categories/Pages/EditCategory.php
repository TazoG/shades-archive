<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Pages;

use App\Domains\Catalog\Models\Category;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

final class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    public function getTitle(): string
    {
        return __('category.pages.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
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
        ];
    }
}
