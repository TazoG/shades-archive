<?php

declare(strict_types=1);

namespace App\Domains\Settings\Services;

use App\Domains\Settings\Enums\CurrencyCode;

final readonly class CurrencyConversionService
{
    public function resolveAmount(
        string $priceGel,
        ?string $explicitUsdPrice,
        CurrencyCode $targetCurrency,
        string $usdExchangeRate,
    ): string {
        if ($targetCurrency === CurrencyCode::GEL) {
            return number_format((float) $priceGel, 2, '.', '');
        }

        if ($explicitUsdPrice !== null && (float) $explicitUsdPrice > 0.0) {
            return number_format((float) $explicitUsdPrice, 2, '.', '');
        }

        $rate = (float) $usdExchangeRate;
        $safeRate = $rate > 0.0 ? $rate : 1.0;

        return number_format((float) $priceGel / $safeRate, 2, '.', '');
    }

    public function toMinorUnits(string $decimalAmount): int
    {
        $normalized = number_format((float) $decimalAmount, 2, '.', '');
        [$whole, $fraction] = explode('.', $normalized);

        $sign = str_starts_with($whole, '-') ? -1 : 1;
        $absWhole = ltrim($whole, '-');

        return $sign * (((int) $absWhole * 100) + (int) $fraction);
    }

    public function format(string $amount, CurrencyCode $currency): string
    {
        return number_format((float) $amount, 2).' '.$currency->symbol();
    }
}
