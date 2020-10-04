<?php

namespace Codanux\MultiLanguage\Traits\HasLanguage;

use Illuminate\Support\Str;

trait HasLanguage
{
    public static function bootHasLanguageTrait()
    {
        self::creating(function ($model) {

            if (is_null($model->translation_of))
            {
                $model->translation_of = Str::uuid();
            }
            if (is_null($model->locale)) {
                $model->locale = app()->getLocale();
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

    public function scopeOrLocale($query, $locale = null)
    {

    }

    public function scopeLocaleSlug($query, $slug, $locale = null)
    {
        if (is_null($locale))
        {
            $locale = app()->getLocale();
        }

        return $query->where('locale_slug', $slug)->first()->translations()->locale($locale);
    }
}
