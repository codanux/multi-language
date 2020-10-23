@props(['component' => 'jet-nav-link', 'translations' => []])

@foreach(config('multi-language.locales') as $locale => $label)
    <x-dynamic-component :component="$component" href="{{ generateLink($locale, $translations) }}">
        {!! $label !!}
    </x-dynamic-component>
@endforeach
