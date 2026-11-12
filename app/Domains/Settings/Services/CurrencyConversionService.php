<?php

declare(strict_types=1);

namespace App\Domains\Settings\Services;

use App\Domains\Settings\Enums\CurrencyCode;

final readonly class CurrencyConversionService
{
    public function resolveAmount(
        float $priceGel,
        ?float $explicitUsdPrice,
        CurrencyCode $targetCurrency,
        float $usdExchangeRate,
    ): float {
        if ($targetCurrency === CurrencyCode::GEL) {
            return round($priceGel, 2);
        }

        if ($explicitUsdPrice !== null && $explicitUsdPrice > 0.0) {
            return round($explicitUsdPrice, 2);
        }

        $safeRate = $usdExchangeRate > 0.0 ? $usdExchangeRate : 1.0;

        return round($priceGel / $safeRate, 2);
    }

    public function format(float $amount, CurrencyCode $currency): string
    {
        return number_format($amount, 2).' '.$currency->symbol();
    }
}
