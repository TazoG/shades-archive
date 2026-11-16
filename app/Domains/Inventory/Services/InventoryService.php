<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Services;

use App\Domains\Catalog\Events\ProductArchived;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductArchivalService;
use App\Domains\Inventory\Data\StockAdjustmentData;
use App\Domains\Inventory\Enums\StockMovementType;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Inventory\Validators\StockAvailabilityValidator;
use App\Domains\Users\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Validation\ValidationException;

final readonly class InventoryService
{
    public function __construct(
        private StockAvailabilityValidator $availabilityValidator,
        private ProductArchivalService $archivalService,
        private Dispatcher $events,
    ) {}

    public function adjustStock(StockAdjustmentData $data, ?User $actor = null): Product
    {
        if ($data->quantityDelta === 0) {
            throw ValidationException::withMessages([
                'quantity_delta' => [__('inventory.exceptions.zero_delta')],
            ]);
        }

        $wasArchivedNow = false;

        $updatedProduct = Product::query()->getConnection()->transaction(
            function () use ($data, $actor, &$wasArchivedNow): Product {
                $product = Product::query()
                    ->with('status')
                    ->lockForUpdate()
                    ->findOrFail($data->productId);

                if ($data->quantityDelta < 0) {
                    $this->availabilityValidator->validateAvailableForSale($product, abs($data->quantityDelta));
                }

                $newStock = max(0, $product->stock_quantity + $data->quantityDelta);

                if ($product->is_one_of_one && $newStock > 1) {
                    throw ValidationException::withMessages([
                        'quantity_delta' => [__('inventory.exceptions.one_of_one_limit')],
                    ]);
                }

                $product->stock_quantity = $newStock;

                if ($newStock === 0) {
                    if ($data->movementType === StockMovementType::Reservation) {
                        $this->archivalService->applyReservedStatus($product);
                    } elseif ($data->movementType === StockMovementType::Sale) {
                        $wasArchivedNow = $this->archivalService->applyArchiveStatus($product);
                    }
                } elseif ($data->quantityDelta > 0) {
                    $this->archivalService->restoreAvailableStatus($product);
                }

                $product->save();

                StockMovement::query()->create([
                    'product_id' => $product->id,
                    'user_id' => $actor?->id,
                    'reference_type' => $data->referenceType,
                    'reference_id' => $data->referenceId,
                    'movement_type' => $data->movementType,
                    'quantity_delta' => $data->quantityDelta,
                    'stock_after' => $newStock,
                    'reason' => $data->reason,
                ]);

                return $product->refresh();
            },
            attempts: 3,
        );

        if ($wasArchivedNow) {
            $this->events->dispatch(new ProductArchived($updatedProduct));
        }

        return $updatedProduct;
    }
}
