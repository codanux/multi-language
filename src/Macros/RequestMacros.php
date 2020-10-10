<?php

namespace Codanux\MultiLanguage\Macros;

use Closure;

class RequestMacros
{
    /**
     * Determine if the route name matches a given pattern in the current
     * locale.
     *
     * @return \Closure
     */
    public function routeIsLocale(): Closure
    {
        return function (...$patterns) {
            $locale = app()->getLocale();

            return $this->routeIs(array_map(function ($pattern) use ($locale) {
                return "{$locale}.{$pattern}";
            }, $patterns));
        };
    }
}
