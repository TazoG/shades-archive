<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domains\Settings\Enums\LocaleCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocaleMiddleware
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionLocale = $request->session()->get('locale');
        $locale = is_string($sessionLocale) ? LocaleCode::tryFrom($sessionLocale) : null;

        if ($locale !== null) {
            app()->setLocale($locale->value);
        }

        return $next($request);
    }
}
