<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Data;

use App\Domains\Inventory\Enums\StockMovementType;
use Spatie\LaravelData\Data;

final class StockAdjustmentData extends Data
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantityDelta,
        public readonly StockMovementType $movementType,
        public readonly ?string $reason = null,
    ) {}
}
