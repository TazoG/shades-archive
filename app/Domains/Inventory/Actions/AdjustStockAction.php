<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Actions;

use App\Domains\Catalog\Models\Product;
use App\Domains\Inventory\Data\StockAdjustmentData;
use App\Domains\Inventory\Services\InventoryService;
use App\Domains\Users\Models\User;

final readonly class AdjustStockAction
{
    public function __construct(
        private InventoryService $inventoryService,
    ) {}

    public function execute(StockAdjustmentData $data, ?User $actor = null): Product
    {
        return $this->inventoryService->adjustStock($data, $actor);
    }
}
