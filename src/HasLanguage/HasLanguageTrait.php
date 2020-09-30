<?php

namespace Codanux\MultiLanguage\HasLanguage;

use Illuminate\Support\Str;

trait HasLanguageTrait
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

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function trans($locale = null)
    {
        if (is_null($locale))
        {
            $locale = app()->getLocale();
        }

        return $this->translations()->locale($locale)->first();
    }

    public function scopeTranslation($query, $slug, $locale = null)
    {
        if (is_null($locale))
        {
            $locale = app()->getLocale();
        }

        return $query->slug($slug)->first()->translations()->locale($locale);
    }
}
