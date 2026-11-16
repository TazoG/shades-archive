<?php

declare(strict_types=1);

use App\Domains\Settings\Enums\LocaleCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/locale/{locale}', static function (Request $request, string $locale): RedirectResponse {
    $resolved = LocaleCode::tryFrom($locale);

    if ($resolved !== null) {
        $request->session()->put('locale', $resolved->value);
    }

    return redirect()->back();
})->name('locale.switch');
