<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Exceptions;

use RuntimeException;

final class MissingReservedStatusException extends RuntimeException
{
    public static function create(): self
    {
        return new self(__('inventory.exceptions.missing_reserved_status'));
    }
}
