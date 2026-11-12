<?php

// app/Domains/Catalog/Policies/CategoryPolicy.php
declare(strict_types=1);

namespace App\Domains\Catalog\Policies;

use App\Domains\Catalog\Models\Category;
use App\Domains\Users\Enums\SystemRole;
use App\Domains\Users\Models\User;

final class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Category $category): bool
    {
        return $user->isStaff() || $category->is_active;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([SystemRole::SuperAdmin->value, SystemRole::ArchiveCurator->value]);
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasAnyRole([SystemRole::SuperAdmin->value, SystemRole::ArchiveCurator->value]);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin) && ! $category->products()->exists();
    }
}
