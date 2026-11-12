<?php

// app/Domains/Catalog/Policies/BrandPolicy.php
declare(strict_types=1);

namespace App\Domains\Catalog\Policies;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Users\Enums\SystemRole;
use App\Domains\Users\Models\User;

final class BrandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->isStaff() || $brand->is_active;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([SystemRole::SuperAdmin->value, SystemRole::ArchiveCurator->value]);
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasAnyRole([SystemRole::SuperAdmin->value, SystemRole::ArchiveCurator->value]);
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin) && ! $brand->products()->exists();
    }
}
