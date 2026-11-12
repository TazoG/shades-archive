<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Actions;

use App\Domains\Catalog\Events\ProductArchived;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductStatus;
use Illuminate\Contracts\Events\Dispatcher;

final readonly class ArchiveProductAction
{
    public function __construct(
        private Dispatcher $events,
    ) {}

    public function execute(Product $product): Product
    {
        $archiveStatus = ProductStatus::query()
            ->where('is_archived', true)
            ->orderBy('sort_order')
            ->firstOrFail();

        $product->update([
            'product_status_id' => $archiveStatus->id,
            'stock_quantity' => 0,
            'archived_at' => now(),
        ]);

        $freshProduct = $product->refresh();
        $this->events->dispatch(new ProductArchived($freshProduct));

        return $freshProduct;
    }
}
