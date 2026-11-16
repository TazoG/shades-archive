@php
    $currentLocale = app()->getLocale();
@endphp

<div
    class="flex items-center gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-0.5 dark:border-zinc-700 dark:bg-zinc-900">
    @foreach (\App\Domains\Settings\Enums\LocaleCode::cases() as $locale)
        <a href="{{ route('locale.switch', ['locale' => $locale->value]) }}" @class([
            'rounded-md px-2 py-1 font-mono text-xs font-semibold uppercase transition',
            'bg-zinc-900 text-white dark:bg-white dark:text-zinc-950' =>
                $currentLocale === $locale->value,
            'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' =>
                $currentLocale !== $locale->value,
        ])>
            {{ $locale->value }}
        </a>
    @endforeach
</div>
