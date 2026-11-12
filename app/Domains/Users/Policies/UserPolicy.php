<?php

declare(strict_types=1);

namespace App\Domains\Users\Policies;

use App\Domains\Users\Enums\SystemRole;
use App\Domains\Users\Models\User;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin) || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin) || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasSystemRole(SystemRole::SuperAdmin) && $user->id !== $model->id;
    }
}
