<?php


namespace Codanux\MultiLanguage\Macros;

use Closure;

class RedirectMacros
{
    public function routeLocale(): Closure
    {
        return function (string $route, $parameters = [], $locale = null, int $status = 302, array $headers = []) {
            if (is_null($locale))
                $locale = app()->getLocale();

            return $this->route("{$locale}.{$route}", $parameters, $status, $headers);
        };
    }
}
