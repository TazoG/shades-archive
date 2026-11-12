<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Policies\BrandPolicy;
use App\Domains\Catalog\Policies\CategoryPolicy;
use App\Domains\Catalog\Policies\ProductPolicy;
use App\Domains\Users\Models\User;
use App\Domains\Users\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! app()->isProduction());

        Gate::policy(User::class, UserPolicy::class);

        Gate::policy(Product::class, ProductPolicy::class);

        Gate::policy(Category::class, CategoryPolicy::class);

        Gate::policy(Brand::class, BrandPolicy::class);
    }
}
