<?php

namespace Codanux\MultiLanguage\Macros;

use Closure;
use Codanux\MultiLanguage\MultiLanguagePendingRouteRegistration;
use Codanux\MultiLanguage\MultiLanguageResourceRegistrar;
use Codanux\MultiLanguage\MultiLanguageRouteRegistrar;

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

            return new MultiLanguagePendingRouteRegistration(
                $registrar, $name, $controller, $options
            );
        };
    }


    public function locale(): Closure
    {
        return function ($name, $controller = null, $options = [])
        {
            if ($this->container && $this->container->bound(MultiLanguageRouteRegistrar::class)) {
                $registrar = $this->container->make(MultiLanguageRouteRegistrar::class);
            } else {
                $registrar = new MultiLanguageRouteRegistrar($this);
            }

            return new MultiLanguagePendingRouteRegistration(
                $registrar, $name, $controller, $options
            );
        };
    }

    public function hasLocale() :Closure
    {
        return function ($name, $locale = null)
        {
            if (is_null($locale))
                $locale = app()->getLocale();

            $names = is_array($name) ? $name : func_get_args();

            foreach ($names as $key => $name)
            {
                if (! $this->routes->hasNamedRoute("{$locale}.{$name}")) {
                    return false;
                }
            }
            return true;
        };
    }

}
