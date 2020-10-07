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

            $options['method'] = "GET";

            return new MultiLanguagePendingRouteRegistration(
                $registrar, $name, $controller, $options
            );
        };
    }


}
