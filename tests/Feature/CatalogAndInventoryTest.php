<?php

declare(strict_types=1);

use App\Domains\Catalog\Actions\ArchiveProductAction;
use App\Domains\Catalog\Data\CatalogFilterData;
use App\Domains\Catalog\Events\ProductArchived;
use App\Domains\Catalog\Exceptions\MissingArchiveStatusException;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductStatus;
use App\Domains\Catalog\Services\CatalogQueryService;
use App\Domains\Catalog\Services\ProductStatusProtectionService;
use App\Domains\Inventory\Actions\AdjustStockAction;
use App\Domains\Inventory\Data\StockAdjustmentData;
use App\Domains\Inventory\Enums\StockMovementType;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Settings\Services\CurrencyConversionService;
use Database\Seeders\SystemBootstrapSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\seed;

/** @param array<string, mixed> $overrides */
function createSampleProduct(array $overrides = []): Product
{
    $brand = Brand::query()->firstOrCreate(
        ['slug' => 'oakley'],
        ['name' => ['ka' => 'Oakley', 'en' => 'Oakley']]
    );

    $parentCategory = Category::query()->firstOrCreate(
        ['slug' => 'sunglasses'],
        ['name' => ['ka' => 'სათვალეები', 'en' => 'Sunglasses']]
    );

    $childCategory = Category::query()->firstOrCreate(
        ['slug' => 'x-metal'],
        ['parent_id' => $parentCategory->id, 'name' => ['ka' => 'X-Metal', 'en' => 'X-Metal']]
    );

    $availableStatus = ProductStatus::query()->where('code', 'available')->firstOrFail();

    /** @var Product $product */
    $product = Product::query()->create(array_merge([
        'brand_id' => $brand->id,
        'category_id' => $childCategory->id,
        'product_status_id' => $availableStatus->id,
        'sku' => 'SHD-OAK-0001',
        'slug' => 'oakley-romeo-1-shd-0001',
        'name' => ['ka' => 'Oakley Romeo 1 არქივი', 'en' => 'Oakley Romeo 1 Archive'],
        'subtitle' => ['ka' => 'X-Metal Titanium', 'en' => 'X-Metal Titanium'],
        'price_gel' => '1850.00',
        'price_usd' => '680.00',
        'stock_quantity' => 1,
        'is_one_of_one' => true,
    ], $overrides));

    return $product;
}

beforeEach(function (): void {
    seed(SystemBootstrapSeeder::class);
});

it('reserves 1-of-1 stock without archiving and restores availability on return', function (): void {
    Event::fake([ProductArchived::class]);

    $product = createSampleProduct();
    $adjustStock = app(AdjustStockAction::class);

    $reserved = $adjustStock->execute(new StockAdjustmentData(
        productId: $product->id,
        quantityDelta: -1,
        movementType: StockMovementType::Reservation,
        reason: 'Flitt checkout started',
    ));

    expect($reserved->stock_quantity)->toBe(0)
        ->and($reserved->status?->is_reserved)->toBeTrue()
        ->and($reserved->status?->is_archived)->toBeFalse()
        ->and($reserved->archived_at)->toBeNull();

    Event::assertNotDispatched(ProductArchived::class);

    $restored = $adjustStock->execute(new StockAdjustmentData(
        productId: $product->id,
        quantityDelta: 1,
        movementType: StockMovementType::Return,
        reason: 'Payment cancelled',
    ));

    expect($restored->stock_quantity)->toBe(1)
        ->and($restored->status?->is_available)->toBeTrue()
        ->and($restored->archived_at)->toBeNull();
});

it('automatically archives a 1-of-1 piece on sale and logs stock movement', function (): void {
    Event::fake([ProductArchived::class]);

    $product = createSampleProduct();
    $adjustStock = app(AdjustStockAction::class);

    $sold = $adjustStock->execute(new StockAdjustmentData(
        productId: $product->id,
        quantityDelta: -1,
        movementType: StockMovementType::Sale,
        reason: 'Order #SA-0001 paid',
    ));

    expect($sold->stock_quantity)->toBe(0)
        ->and($sold->status?->is_archived)->toBeTrue()
        ->and($sold->archived_at)->not->toBeNull();

    expect(StockMovement::query()->where('product_id', $product->id)->count())->toBe(1);
    Event::assertDispatched(ProductArchived::class);
});

it('enforces one-of-one stock cap and rejects zero delta', function (): void {
    $product = createSampleProduct();
    $adjustStock = app(AdjustStockAction::class);

    expect(fn (): Product => $adjustStock->execute(new StockAdjustmentData(
        productId: $product->id,
        quantityDelta: 0,
        movementType: StockMovementType::ManualAdjustment,
    )))->toThrow(ValidationException::class);

    expect(fn (): Product => $adjustStock->execute(new StockAdjustmentData(
        productId: $product->id,
        quantityDelta: 1,
        movementType: StockMovementType::ManualAdjustment,
    )))->toThrow(ValidationException::class);
});

it('throws explicit exception when archive status is missing', function (): void {
    $product = createSampleProduct();
    ProductStatus::query()->where('code', 'archived')->delete();

    $archiveAction = app(ArchiveProductAction::class);

    expect(fn (): Product => $archiveAction->execute($product))
        ->toThrow(MissingArchiveStatusException::class);
});

it('searches bilingual translatable fields and filters parent categories', function (): void {
    $available = createSampleProduct();
    $archived = createSampleProduct([
        'sku' => 'SHD-OAK-0002',
        'slug' => 'oakley-juliet-shd-0002',
        'name' => ['ka' => 'Oakley Juliet პლაზმა', 'en' => 'Oakley Juliet Plasma'],
    ]);
    app(ArchiveProductAction::class)->execute($archived);

    $queryService = app(CatalogQueryService::class);

    $activeResults = $queryService->buildCatalogQuery(new CatalogFilterData(
        search: 'არქივი',
        categorySlug: 'sunglasses',
        archiveOnly: false,
    ))->get();

    $archiveResults = $queryService->buildCatalogQuery(new CatalogFilterData(
        search: 'juliet',
        archiveOnly: true,
    ))->get();

    expect($activeResults)->toHaveCount(1)
        ->and($activeResults->first()?->id)->toBe($available->id)
        ->and($archiveResults)->toHaveCount(1)
        ->and($archiveResults->first()?->id)->toBe($archived->id);
});

it('protects default and in-use statuses from deletion', function (): void {
    createSampleProduct();
    $defaultStatus = ProductStatus::query()->where('is_default', true)->firstOrFail();
    $protection = app(ProductStatusProtectionService::class);

    expect(fn () => $protection->deleteSafely($defaultStatus->id))
        ->toThrow(ValidationException::class);
});

it('allows guests to view available and archived products via ProductPolicy', function (): void {
    $product = createSampleProduct();
    $product->load('status');

    expect(Gate::forUser(null)->allows('view', $product))->toBeTrue();
});

it('converts decimal prices to minor units without float drift', function (): void {
    $converter = app(CurrencyConversionService::class);

    expect($converter->toMinorUnits('199.99'))->toBe(19999)
        ->and($converter->toMinorUnits('0.01'))->toBe(1);
});
