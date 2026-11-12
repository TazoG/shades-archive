<?php

declare(strict_types=1);

namespace App\Domains\Settings\Models;

use App\Domains\Settings\Enums\CurrencyCode;
use Illuminate\Database\Eloquent\Model;

final class StoreSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'default_currency',
        'usd_exchange_rate',
        'contact_email',
        'contact_phone',
        'instagram_handle',
        'worldwide_shipping_enabled',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_currency' => CurrencyCode::class,
            'usd_exchange_rate' => 'float',
            'worldwide_shipping_enabled' => 'boolean',
        ];
    }
}
