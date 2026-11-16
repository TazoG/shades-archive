<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Models\ProductStatus;
use App\Domains\Catalog\Validators\StatusDeletionValidator;

final readonly class ProductStatusProtectionService
{
    public function __construct(
        private StatusDeletionValidator $validator,
    ) {}

    public function deleteSafely(int $statusId): void
    {
        $status = ProductStatus::query()->findOrFail($statusId);

        $this->validator->validateCanDelete($status);

        $status->delete();
    }
}
