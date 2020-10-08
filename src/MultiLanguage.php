<?php

namespace Codanux\MultiLanguage;

class MultiLanguage
{
    public static function generateUri($name, $locale, $method = null)
    {
        if (is_null($method))
        {
            $uri = trans("routes.{$name}", [], $locale);
        } else {
            $uri = trans("routes.{$name}.{$method}", [], $locale);
        }

        if (config('multi-language.default_prefix') ||
            ! ($locale == config('multi-language.default_locale')))
        {
            $uri = "{$locale}/{$uri}";
        }

        return $uri;
    }

}
