<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Data;

use Spatie\LaravelData\Data;

final class CatalogFilterData extends Data
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $brandSlug = null,
        public readonly ?string $categorySlug = null,
        public readonly ?string $eraSlug = null,
        public readonly ?string $frameColorSlug = null,
        public readonly ?string $lensColorSlug = null,
        public readonly bool $archiveOnly = false,
        public readonly string $sortBy = 'latest',
    ) {}
}
