<?php

declare(strict_types=1);

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class Product extends Model
{
    use HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name', 'subtitle', 'description', 'provenance_notes'];

    /** @var list<string> */
    protected $fillable = [
        'brand_id',
        'category_id',
        'product_status_id',
        'era_id',
        'frame_color_id',
        'lens_color_id',
        'condition_grade_id',
        'product_type',
        'sku',
        'slug',
        'model_code',
        'serial_number',
        'release_year',
        'name',
        'subtitle',
        'description',
        'provenance_notes',
        'price_gel',
        'price_usd',
        'compare_at_price_gel',
        'stock_quantity',
        'is_one_of_one',
        'is_featured',
        'archived_at',
    ];

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<ProductStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

    /**
     * @return BelongsTo<Era, $this>
     */
    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }

    /**
     * @return BelongsTo<FrameColor, $this>
     */
    public function frameColor(): BelongsTo
    {
        return $this->belongsTo(FrameColor::class);
    }

    /**
     * @return BelongsTo<LensColor, $this>
     */
    public function lensColor(): BelongsTo
    {
        return $this->belongsTo(LensColor::class);
    }

    /**
     * @return BelongsTo<ConditionGrade, $this>
     */
    public function conditionGrade(): BelongsTo
    {
        return $this->belongsTo(ConditionGrade::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'product_type' => ProductType::class,
            'release_year' => 'integer',
            'price_gel' => 'float',
            'price_usd' => 'float',
            'compare_at_price_gel' => 'float',
            'stock_quantity' => 'integer',
            'is_one_of_one' => 'boolean',
            'is_featured' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }
}
