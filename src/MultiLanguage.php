<?php

namespace Codanux\MultiLanguage;

class MultiLanguage
{
    public static function generateUri(string $name, string $locale, bool $prefix = true)
    {
        $uri = trans("routes.{$name}", [], $locale);

        if ($prefix && (config('multi-language.default_prefix') ||
            ! ($locale == config('multi-language.default_locale'))))
        {
            $uri = "{$locale}/{$uri}";
        }

        return $uri;
    }

}
