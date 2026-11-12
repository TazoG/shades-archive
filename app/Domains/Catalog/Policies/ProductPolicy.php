<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Policies;

use App\Domains\Catalog\Models\Product;
use App\Domains\Users\Enums\SystemRole;
use App\Domains\Users\Models\User;

final class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->isStaff()) {
            return true;
        }

        $product->loadMissing('status');
        $status = $product->status;

        return $status !== null && ($status->is_available || $status->is_archived);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            SystemRole::SuperAdmin->value,
            SystemRole::ArchiveCurator->value,
        ]);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasAnyRole([
            SystemRole::SuperAdmin->value,
            SystemRole::ArchiveCurator->value,
        ]);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin);
    }
}
