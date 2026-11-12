<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Services;

use App\Domains\Catalog\Events\ProductArchived;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductStatus;
use App\Domains\Inventory\Data\StockAdjustmentData;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Inventory\Validators\StockAvailabilityValidator;
use App\Domains\Users\Models\User;
use Illuminate\Contracts\Events\Dispatcher;

final readonly class InventoryService
{
    public function __construct(
        private StockAvailabilityValidator $availabilityValidator,
        private Dispatcher $events,
    ) {}

    public function adjustStock(StockAdjustmentData $data, ?User $actor = null): Product
    {
        $wasArchivedNow = false;

        $updatedProduct = Product::query()->getConnection()->transaction(function () use ($data, $actor, &$wasArchivedNow): Product {
            $product = Product::query()
                ->with('status')
                ->lockForUpdate()
                ->findOrFail($data->productId);

            if ($data->quantityDelta < 0) {
                $this->availabilityValidator->validateAvailableForSale($product, abs($data->quantityDelta));
            }

            $newStock = max(0, $product->stock_quantity + $data->quantityDelta);
            $product->stock_quantity = $newStock;

            $currentStatus = $product->status;

            if ($newStock === 0 && ($currentStatus === null || ! $currentStatus->is_archived)) {
                $archiveStatus = ProductStatus::query()
                    ->where('is_archived', true)
                    ->orderBy('sort_order')
                    ->first();

                if ($archiveStatus !== null) {
                    $product->product_status_id = $archiveStatus->id;
                    $product->archived_at = now();
                    $wasArchivedNow = true;
                }
            }

            $product->save();

            StockMovement::query()->create([
                'product_id' => $product->id,
                'user_id' => $actor?->id,
                'movement_type' => $data->movementType,
                'quantity_delta' => $data->quantityDelta,
                'stock_after' => $newStock,
                'reason' => $data->reason,
            ]);

            return $product->refresh();
        });

        if ($wasArchivedNow) {
            $this->events->dispatch(new ProductArchived($updatedProduct));
        }

        return $updatedProduct;
    }
}
