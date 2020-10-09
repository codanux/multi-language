@props(['component' => 'jet-nav-link', 'translations' => []])
@foreach(config('multi-language.locales') as $locale)
    <x-dynamic-component :component="$component" href="{{ generateLink($locale, $translations) }}">
        {{ strtoupper($locale) }}
    </x-dynamic-component>
@endforeach
