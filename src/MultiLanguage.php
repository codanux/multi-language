<?php

namespace Codanux\MultiLanguage;

use Illuminate\Support\Facades\Lang;

class MultiLanguage
{
    public static function generateUri(string $name, string $locale, bool $prefix = true)
    {
        $key = "routes.{$name}";

        $uri  = Lang::has($key) ? Lang::get($key, [], $locale) : $name;

        if ($prefix && (config('multi-language.default_prefix') ||
            ! ($locale == config('multi-language.default_locale'))))
        {
            $uri = "{$locale}/{$uri}";
        }

        return $uri;
    }

}
