<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Data\CatalogFilterData;
use App\Domains\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class CatalogQueryService
{
    /**
     * @return Builder<Product>
     */
    public function buildCatalogQuery(CatalogFilterData $filter): Builder
    {
        $query = Product::query()
            ->with([
                'brand',
                'category',
                'status',
                'era',
                'frameColor',
                'lensColor',
                'conditionGrade',
                'primaryImage',
                'images',
            ])
            ->whereHas('status', static function (Builder $statusQuery) use ($filter): void {
                if ($filter->archiveOnly) {
                    $statusQuery->where('is_archived', true);
                } else {
                    $statusQuery->where('is_available', true);
                }
            });

        if (! $filter->archiveOnly) {
            $query->where('stock_quantity', '>', 0);
        }

        if ($filter->search !== null && $filter->search !== '') {
            $escaped = addcslashes($filter->search, '%_\\');
            $term = '%'.$escaped.'%';

            $query->where(static function (Builder $searchQuery) use ($term, $escaped): void {
                $searchQuery
                    ->where('sku', 'like', $term)
                    ->orWhere('model_code', 'like', $term)
                    ->orWhere('serial_number', 'like', $term)
                    ->orWhere(static function (Builder $translationQuery) use ($escaped): void {
                        $translationQuery->whereTranslationLike(['name', 'subtitle'], $escaped, ['ka', 'en']);
                    });
            });
        }

        if ($filter->brandSlug !== null && $filter->brandSlug !== '') {
            $query->whereHas('brand', static fn (Builder $q): Builder => $q->where('slug', $filter->brandSlug));
        }

        if ($filter->categorySlug !== null && $filter->categorySlug !== '') {
            $slug = $filter->categorySlug;
            $query->whereHas('category', static function (Builder $q) use ($slug): void {
                $q->where('slug', $slug)
                    ->orWhereHas('parent', static fn (Builder $parentQuery): Builder => $parentQuery->where('slug', $slug));
            });
        }

        if ($filter->eraSlug !== null && $filter->eraSlug !== '') {
            $query->whereHas('era', static fn (Builder $q): Builder => $q->where('slug', $filter->eraSlug));
        }

        if ($filter->frameColorSlug !== null && $filter->frameColorSlug !== '') {
            $query->whereHas('frameColor', static fn (Builder $q): Builder => $q->where('slug', $filter->frameColorSlug));
        }

        if ($filter->lensColorSlug !== null && $filter->lensColorSlug !== '') {
            $query->whereHas('lensColor', static fn (Builder $q): Builder => $q->where('slug', $filter->lensColorSlug));
        }

        return match ($filter->sortBy) {
            'price_asc' => $query->orderBy('price_gel'),
            'price_desc' => $query->orderByDesc('price_gel'),
            'oldest_era' => $query->orderBy('release_year'),
            default => $query->latest(),
        };
    }
}
