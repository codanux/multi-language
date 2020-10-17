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
        foreach($translations as $key => $trans)
        {
            $parameters = array_merge($parameters, [$key => $trans->translations()->locale($locale)->first()]);
        }

        return app('url')->route($locale . '.' . substr(Route::currentRouteName(), 3), $parameters);
    }
}
