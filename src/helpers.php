<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('routeLocalized')) {
    function routeLocalized($name, $parameters = [], $locale = null, $absolute = true)
    {
        if (is_null($locale)){
            $locale = app()->getLocale();
        }

        return app('url')->route($locale . '.' . $name, $parameters, $absolute);
    }
}

if (! function_exists('generateLink')) {
    function generateLink($locale = null, $translations = [])
    {
        $parameters = Route::current()->parameters();
        $parameters = array_merge($parameters, [
            'locale' => $locale
        ]);

        foreach($translations as $key => $trans)
        {
            $parameters = array_merge($parameters, [$key => $trans->translations()->locale($locale)->first() ?? $trans]);
        }

        $name = explode('.', Route::currentRouteName());

        array_splice($name, 0, 1, $locale);

        return app('url')->route(join('.', $name), $parameters);
    }
}
