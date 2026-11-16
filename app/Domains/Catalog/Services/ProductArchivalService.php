<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Exceptions\MissingArchiveStatusException;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductStatus;

final readonly class ProductArchivalService
{
    public function applyArchiveStatus(Product $product): bool
    {
        $product->loadMissing('status');

        if ($product->status?->is_archived === true) {
            return false;
        }

        $archiveStatus = ProductStatus::query()
            ->where('is_archived', true)
            ->orderBy('sort_order')
            ->first();

        if ($archiveStatus === null) {
            throw MissingArchiveStatusException::create();
        }

        $product->product_status_id = $archiveStatus->id;
        $product->archived_at = now();

        return true;
    }

    public function applyReservedStatus(Product $product): void
    {
        $reservedStatus = ProductStatus::query()
            ->where('is_reserved', true)
            ->orderBy('sort_order')
            ->first();

        if ($reservedStatus !== null) {
            $product->product_status_id = $reservedStatus->id;
        }
    }

    public function restoreAvailableStatus(Product $product): void
    {
        $product->loadMissing('status');
        $status = $product->status;

        if ($status === null || (! $status->is_reserved && ! $status->is_archived)) {
            return;
        }

        $defaultStatus = ProductStatus::query()
            ->where('is_default', true)
            ->where('is_available', true)
            ->first()
            ?? ProductStatus::query()->where('is_available', true)->orderBy('sort_order')->first();

        if ($defaultStatus !== null) {
            $product->product_status_id = $defaultStatus->id;
            $product->archived_at = null;
        }
    }
}
