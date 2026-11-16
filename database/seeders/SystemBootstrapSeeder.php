<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Catalog\Models\ProductStatus;
use App\Domains\Settings\Enums\CurrencyCode;
use App\Domains\Settings\Models\StoreSetting;
use App\Domains\Users\Enums\SystemRole;
use App\Domains\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

final class SystemBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SystemRole::cases() as $role) {
            Role::findOrCreate($role->value, 'web');
        }

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@shades-archive.test'],
            [
                'name' => 'Shades Archive Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        if (! $admin->hasRole(SystemRole::SuperAdmin->value)) {
            $admin->assignRole(SystemRole::SuperAdmin->value);
        }

        if (! StoreSetting::query()->exists()) {
            StoreSetting::query()->create([
                'default_currency' => CurrencyCode::GEL,
                'usd_exchange_rate' => '2.7500',
                'instagram_handle' => '@shades.arch1ve',
                'worldwide_shipping_enabled' => true,
            ]);
        }

        $statuses = [
            [
                'code' => 'available',
                'name' => ['ka' => 'ხელმისაწვდომია', 'en' => 'Available Now'],
                'color' => '#16a34a',
                'is_default' => true,
                'is_available' => true,
                'is_reserved' => false,
                'is_archived' => false,
                'sort_order' => 1,
            ],
            [
                'code' => 'reserved',
                'name' => ['ka' => 'დარეზერვებულია', 'en' => 'Reserved'],
                'color' => '#d97706',
                'is_default' => false,
                'is_available' => false,
                'is_reserved' => true,
                'is_archived' => false,
                'sort_order' => 2,
            ],
            [
                'code' => 'archived',
                'name' => ['ka' => 'არქივი (გაყიდული)', 'en' => 'Archive Vault'],
                'color' => '#18181b',
                'is_default' => false,
                'is_available' => false,
                'is_reserved' => false,
                'is_archived' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($statuses as $status) {
            ProductStatus::query()->firstOrCreate(
                ['code' => $status['code']],
                $status,
            );
        }
    }
}
