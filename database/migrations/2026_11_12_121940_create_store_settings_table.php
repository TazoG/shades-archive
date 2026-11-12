<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('default_currency', 3)->default('GEL');
            $table->decimal('usd_exchange_rate', 10, 4)->default(2.7500);
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('instagram_handle')->default('@shades.arch1ve');
            $table->boolean('worldwide_shipping_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
