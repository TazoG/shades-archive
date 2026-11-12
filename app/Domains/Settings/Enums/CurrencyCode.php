<?php

declare(strict_types=1);

namespace App\Domains\Settings\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CurrencyCode: string implements HasColor, HasIcon, HasLabel
{
    case GEL = 'GEL';
    case USD = 'USD';

    public function symbol(): string
    {
        return match ($this) {
            self::GEL => '₾',
            self::USD => '$',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::GEL => __('settings.currencies.gel'),
            self::USD => __('settings.currencies.usd'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::GEL => 'success',
            self::USD => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::GEL => 'heroicon-o-banknotes',
            self::USD => 'heroicon-o-currency-dollar',
        };
    }
}
