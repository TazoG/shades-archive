<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasColor, HasIcon, HasLabel
{
    case Sunglasses = 'sunglasses';
    case Watch = 'watch';
    case Backpack = 'backpack';
    case Accessory = 'accessory';

    public function getLabel(): string
    {
        return match ($this) {
            self::Sunglasses => __('product.types.sunglasses'),
            self::Watch => __('product.types.watch'),
            self::Backpack => __('product.types.backpack'),
            self::Accessory => __('product.types.accessory'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Sunglasses => 'primary',
            self::Watch => 'warning',
            self::Backpack => 'info',
            self::Accessory => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Sunglasses => 'heroicon-o-eye',
            self::Watch => 'heroicon-o-clock',
            self::Backpack => 'heroicon-o-briefcase',
            self::Accessory => 'heroicon-o-sparkles',
        };
    }
}
