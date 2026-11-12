<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Models;

use App\Domains\Catalog\Models\Product;
use App\Domains\Inventory\Enums\StockMovementType;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class StockMovement extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'product_id',
        'user_id',
        'movement_type',
        'quantity_delta',
        'stock_after',
        'reason',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'movement_type' => StockMovementType::class,
            'quantity_delta' => 'integer',
            'stock_after' => 'integer',
        ];
    }
}
