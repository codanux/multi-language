<?php

namespace Codanux\MultiLanguage\Traits\HasMedia;

use Illuminate\Support\Collection;

trait MediaTrait {

    public function getMedia(string $collectionName = 'default', $filters = []): Collection
    {
        $mediaRepository = config('multi-language.media.media_repository');

        $media = $this->customMedia($this, $collectionName, $filters, $mediaRepository);

        if ($media->count())
            return $media;


        $locale = config('multi-language.media.locale');

        if (config('multi-language.media.translations_detect'))
        {
            $translations = $this->translations()->get();

            if ($first = $translations->firstWhere('locale', $locale)) {
                $translations = $translations->prepend($first)->unique();
            }

            foreach ($translations as $trans)
            {
                $media = $this->customMedia($trans, $collectionName, $filters, $mediaRepository);

                if ($media->count())
                    return $media;
            }
        }
        else if ($locale)
        {
            $media = $this->customMedia($this->locale($locale)->first(), $collectionName, $filters, $mediaRepository);
        }


        return $media;
    }

    protected function customMedia($class, $collectionName, $filters, $mediaRepository)
    {
        return app($mediaRepository)->getCollection($class, $collectionName, $filters);
    }
}
