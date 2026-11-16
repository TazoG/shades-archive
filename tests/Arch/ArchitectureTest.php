<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

arch('app files use strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('no raw DB facade usage anywhere in app')
    ->expect(DB::class)
    ->not->toBeUsedIn('App');

arch('no inline HTML helpers in PHP classes')
    ->expect([HtmlString::class, Blade::class])
    ->not->toBeUsedIn('App');

arch('domains do not depend on UI or external integrations')
    ->expect('App\Domains')
    ->not->toUse(['App\Filament', 'App\Livewire', 'App\Http']);
