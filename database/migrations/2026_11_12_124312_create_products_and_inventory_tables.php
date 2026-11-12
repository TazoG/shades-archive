<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('product_status_id')->constrained('product_statuses')->restrictOnDelete();
            $table->foreignId('era_id')->nullable()->constrained('eras')->nullOnDelete();
            $table->foreignId('frame_color_id')->nullable()->constrained('frame_colors')->nullOnDelete();
            $table->foreignId('lens_color_id')->nullable()->constrained('lens_colors')->nullOnDelete();
            $table->foreignId('condition_grade_id')->nullable()->constrained('condition_grades')->nullOnDelete();

            $table->string('product_type', 32)->index();
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->string('model_code')->nullable();
            $table->string('serial_number')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();

            $table->json('name');
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->json('provenance_notes')->nullable();

            $table->decimal('price_gel', 12, 2);
            $table->decimal('price_usd', 12, 2)->nullable();
            $table->decimal('compare_at_price_gel', 12, 2)->nullable();

            $table->unsignedInteger('stock_quantity')->default(1);
            $table->boolean('is_one_of_one')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('archived_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('path');
            $table->json('alt_text')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('movement_type', 32);
            $table->integer('quantity_delta');
            $table->unsignedInteger('stock_after');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
