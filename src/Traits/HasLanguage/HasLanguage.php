<?php

namespace Codanux\MultiLanguage\Traits\HasLanguage;

use Illuminate\Support\Str;

trait HasLanguage
{
    public static function bootHasLanguage()
    {
        self::creating(function ($model)
        {
            if (is_null($model->translation_of))
            {
                $model->translation_of = Str::uuid();
            }
        });
    }

    public function translations()
    {
        return $this->hasMany(self::class, 'translation_of', 'translation_of');
    }

    public function scopeLocale($query, $locale = null)
    {
        if (is_null($locale))
        {
            $locale = app()->getLocale();
        }

        return $query->where('locale', $locale);
    }

    public function scopeLocaleSlug($query, $slug, $locale = null)
    {
        if (is_null($locale))
        {
            $locale = app()->getLocale();
        }

        return $query->where('locale_slug', $slug)->first()->translations()->locale($locale);
    }

    public function getLabel()
    {
        return $this->getKey();
    }
}
