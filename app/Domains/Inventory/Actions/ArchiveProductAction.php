<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Actions;

use App\Domains\Catalog\Events\ProductArchived;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Services\ProductArchivalService;
use App\Domains\Inventory\Enums\StockMovementType;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Users\Models\User;
use Illuminate\Contracts\Events\Dispatcher;

final readonly class ArchiveProductAction
{
    public function __construct(
        private ProductArchivalService $archivalService,
        private Dispatcher $events,
    ) {}

    public function execute(Product $product, ?User $actor = null, ?string $reason = null): Product
    {
        $wasArchivedNow = false;

        $archivedProduct = Product::query()->getConnection()->transaction(
            function () use ($product, $actor, $reason, &$wasArchivedNow): Product {
                $locked = Product::query()
                    ->with('status')
                    ->lockForUpdate()
                    ->findOrFail($product->id);

                $delta = -$locked->stock_quantity;
                $locked->stock_quantity = 0;

                $wasArchivedNow = $this->archivalService->applyArchiveStatus($locked);
                $locked->save();

                if ($delta !== 0) {
                    StockMovement::query()->create([
                        'product_id' => $locked->id,
                        'user_id' => $actor?->id,
                        'movement_type' => StockMovementType::ManualAdjustment,
                        'quantity_delta' => $delta,
                        'stock_after' => 0,
                        'reason' => $reason ?? 'Manual archive transition',
                    ]);
                }

                return $locked->refresh();
            },
            attempts: 3,
        );

        if ($wasArchivedNow) {
            $this->events->dispatch(new ProductArchived($archivedProduct));
        }

        return $archivedProduct;
    }
}
