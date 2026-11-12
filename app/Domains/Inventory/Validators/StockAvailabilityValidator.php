<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Validators;

use App\Domains\Catalog\Models\Product;
use Illuminate\Validation\ValidationException;

final readonly class StockAvailabilityValidator
{
    public function validateAvailableForSale(Product $product, int $requestedQuantity): void
    {
        $product->loadMissing('status');
        $status = $product->status;

        if ($status === null || ! $status->is_available || $product->stock_quantity < $requestedQuantity || $requestedQuantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => [__('inventory.exceptions.insufficient_stock', [
                    'product' => $product->name,
                ])],
            ]);
        }
    }
}
