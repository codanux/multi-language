<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('routeLocalized')) {
    function routeLocalized($name, $parameters = [], $absolute = true, $locale = null)
    {
        if (is_null($locale)){
            $locale = app()->getLocale();
        }

        return app('url')->route($locale . '.' . $name, $parameters, $absolute);
    }
}

if (! function_exists('generateLink')) {
    function generateLink($lang = null, $translations = [])
    {
        $parameters = Route::current()->parameters();
        foreach($translations as $key => $trans)
        {
            $parameters = array_merge($parameters, [$key => $trans->getRouteKey()]);
        }

        return app('url')->route($lang . '.' . substr(Route::currentRouteName(), 3), $parameters);
    }
}
