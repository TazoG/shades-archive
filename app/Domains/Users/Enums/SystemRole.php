<?php

declare(strict_types=1);

namespace App\Domains\Users\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SystemRole: string implements HasColor, HasIcon, HasLabel
{
    case SuperAdmin = 'super_admin';
    case ArchiveCurator = 'archive_curator';
    case OrderManager = 'order_manager';
    case Customer = 'customer';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => __('user.roles.super_admin'),
            self::ArchiveCurator => __('user.roles.archive_curator'),
            self::OrderManager => __('user.roles.order_manager'),
            self::Customer => __('user.roles.customer'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'danger',
            self::ArchiveCurator => 'warning',
            self::OrderManager => 'info',
            self::Customer => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::SuperAdmin => 'heroicon-o-shield-check',
            self::ArchiveCurator => 'heroicon-o-archive-box',
            self::OrderManager => 'heroicon-o-shopping-bag',
            self::Customer => 'heroicon-o-user',
        };
    }
}
