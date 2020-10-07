<?php

namespace Codanux\MultiLanguage;

use Closure;
use Dotenv\Dotenv;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

class RouterMacros
{
    public function localeResource(): Closure
    {
        return function ($name, $controller = null, $options = [])
        {
            if ($this->container && $this->container->bound(MultiLanguageResourceRegistrar::class)) {
                $registrar = $this->container->make(MultiLanguageResourceRegistrar::class);
            } else {
                $registrar = new MultiLanguageResourceRegistrar($this);
            }

            return new MultiLanguagePendingResourceRegistration(
                $registrar, $name, $controller, $options
            );
        };
    }

    public function locale(): Closure
    {
        return function ($type, $name, $action = null) {
            foreach (config('multi-language.locales') as $key => $locale)
            {
                $uri = trans("routes.{$name}", [], $locale);

                if (config('multi-language.default_prefix')) {
                    $uri = "{$locale}/{$uri}";
                }
                else if (! ($locale == config('multi-language.default_locale'))) {
                    $uri = "{$locale}/{$uri}";
                }

                $route = Route::$type($uri, $action);

                if (! is_null($name)) {
                    $route->name("{$locale}.{$name}");
                }
            }
        };
    }


}
