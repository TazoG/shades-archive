<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StockMovementType: string implements HasColor, HasIcon, HasLabel
{
    case Initial = 'initial';
    case Sale = 'sale';
    case Return = 'return';
    case ManualAdjustment = 'manual_adjustment';
    case Reservation = 'reservation';

    public function getLabel(): string
    {
        return match ($this) {
            self::Initial => __('inventory.movement_types.initial'),
            self::Sale => __('inventory.movement_types.sale'),
            self::Return => __('inventory.movement_types.return'),
            self::ManualAdjustment => __('inventory.movement_types.manual_adjustment'),
            self::Reservation => __('inventory.movement_types.reservation'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Initial => 'info',
            self::Sale => 'success',
            self::Return => 'warning',
            self::ManualAdjustment => 'gray',
            self::Reservation => 'primary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Initial => 'heroicon-o-plus-circle',
            self::Sale => 'heroicon-o-shopping-bag',
            self::Return => 'heroicon-o-arrow-uturn-left',
            self::ManualAdjustment => 'heroicon-o-adjustments-horizontal',
            self::Reservation => 'heroicon-o-lock-closed',
        };
    }
}
