@foreach(config('multi-language.locales') as $locale)
    <li class="{{ config('multi-language.links.li.class').' '.(app()->getLocale() == $locale ? config('multi-language.links.li.active_class') : config('multi-language.links.li.inactive_class')) }}">
        <a class="{{ config('multi-language.links.a.class') }}" href="{{ generateLink($locale, $translations ?? []) }}">{{ $locale }}</a>
    </li>
@endforeach
