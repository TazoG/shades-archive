<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Schemas;

use App\Domains\Catalog\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make(__('category.sections.translations'))
                ->tabs([
                    Tab::make(__('common.locales.ka'))
                        ->schema([
                            TextInput::make('name.ka')
                                ->label(__('category.fields.name'))
                                ->required()
                                ->maxLength(255),
                            Textarea::make('description.ka')
                                ->label(__('category.fields.description'))
                                ->rows(3),
                        ]),
                    Tab::make(__('common.locales.en'))
                        ->schema([
                            TextInput::make('name.en')
                                ->label(__('category.fields.name'))
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(static function (Get $get, Set $set, ?string $state): void {
                                    $currentSlug = $get('slug');
                                    if ($state !== null && (! is_string($currentSlug) || $currentSlug === '')) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            Textarea::make('description.en')
                                ->label(__('category.fields.description'))
                                ->rows(3),
                        ]),
                ])
                ->columnSpanFull(),

            Section::make(__('category.sections.general'))
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('parent_id')
                            ->label(__('category.fields.parent'))
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                ignoreRecord: true,
                            )
                            ->getOptionLabelFromRecordUsing(
                                static fn (Category $record): string => $record->name,
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('slug')
                            ->label(__('category.fields.slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label(__('category.fields.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('category.fields.is_active'))
                            ->default(true)
                            ->inline(false),
                    ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
