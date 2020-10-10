<?php


namespace Codanux\MultiLanguage\Macros;

use Closure;

class RedirectMacros
{
    public function routeLocale(): Closure
    {
        return function (string $route, $parameters = [], int $status = 302, array $headers = []) {
            $locale = app()->getLocale();

            return $this->route("{$locale}.{$route}", $parameters, $status, $headers);
        };
    }
}
