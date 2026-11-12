<?php

declare(strict_types=1);

namespace App\Domains\Settings\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LocaleCode: string implements HasColor, HasIcon, HasLabel
{
    case KA = 'ka';
    case EN = 'en';

    public function getLabel(): string
    {
        return match ($this) {
            self::KA => __('settings.locales.ka'),
            self::EN => __('settings.locales.en'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::KA => 'danger',
            self::EN => 'primary',
        };
    }

    public function getIcon(): string
    {
        return 'heroicon-o-language';
    }
}
