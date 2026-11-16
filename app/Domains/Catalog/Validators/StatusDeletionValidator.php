<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Validators;

use App\Domains\Catalog\Models\ProductStatus;
use Illuminate\Validation\ValidationException;

final readonly class StatusDeletionValidator
{
    public function validateCanDelete(ProductStatus $status): void
    {
        if ($status->is_default) {
            throw ValidationException::withMessages([
                'product_status_id' => [__('product.exceptions.cannot_delete_default_status')],
            ]);
        }

        if ($status->products()->exists()) {
            throw ValidationException::withMessages([
                'product_status_id' => [__('product.exceptions.status_in_use')],
            ]);
        }
    }
}
