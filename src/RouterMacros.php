<?php

namespace Codanux\MultiLanguage;

use Closure;
use Illuminate\Support\Facades\Route;

class RouterMacros
{

    public function localeResource(): Closure
    {
        return function ($name, $controller = null, $options = [])
        {
            return new MultiLanguageRoutePendingRegistration(
                $this->container && $this->container->bound(MultiLanguageRegistrar::class)
                    ? $this->container->make(MultiLanguageRegistrar::class)
                    : new MultiLanguageRegistrar($this),
                $name,
                $controller,
                $options
            );
        };
    }

    public function locale(): Closure
    {
        return function ($type, $name, $action = null) {
            foreach (config('multi-language.locales') as $key => $locale)
            {

                $uri = trans("routes.{$name}", [], $locale);
                $route = Route::$type($uri, $action);

                if (config('multi-language.default_prefix')) {
                    $route->prefix($locale);
                }
                else {
                    $route->prefix($locale == config('multi-language.default_locale') ? null : $locale);
                }


                if (! is_null($name)) {
                    $route->name($locale.'.'.$name);
                }
            }
        };
    }

    public function localeGet(): Closure
    {
        return function ($name, $action = null) {
            foreach (config('multi-language.locales') as $key => $locale)
            {
                $route = Route::get(trans("routes.{$name}", [], $locale), $action);
                $route->prefix($locale == config('multi-language.default_locale') ? null : $locale);
                if (! is_null($name)) {
                    $route->name($locale.'.'.$name);
                }
            }
        };
    }
}
