<?php

namespace Codanux\MultiLanguage\Traits\HasMedia;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\MediaRepository;

trait MediaTrait {

    use HasMediaTrait;

    public function getMedia(string $collectionName = 'default', $filters = []): Collection
    {
        if ($media = app(MediaRepository::class)->getCollection($this, $collectionName, $filters))
        {
            if ($media->count()) {
                return $media;
            }
        }

        // if not media default locale media select
        return app(MediaRepository::class)->getCollection($this->trans(config('multi-language.default_locale')), $collectionName, $filters);
    }
}
