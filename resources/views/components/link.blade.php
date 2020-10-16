@props(['component' => 'jet-nav-link', 'parameters' => request()->route()->parameters(), 'name', 'locale' => app()->getLocale()])
<x-dynamic-component :component="$component" href="{{ routeLocalized($name, $parameters, $locale) }}" :active="request()->routeIsLocale($name)">
    {!! Route::getRoutes()->getByName(app()->getLocale().'.'.$name)->getAction('label') !!}
</x-dynamic-component>
