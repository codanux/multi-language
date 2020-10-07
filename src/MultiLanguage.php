<?php

namespace Codanux\MultiLanguage;

class MultiLanguage
{
    public static function generateUri($name, $locale)
    {
        $uri = trans("routes.{$name}", [], $locale);

        if (config('multi-language.default_prefix') ||
            ! ($locale == config('multi-language.default_locale')))
        {
            $uri = "{$locale}/{$uri}";
        }

        return $uri;
    }

}
