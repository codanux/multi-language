<?php

namespace Codanux\MultiLanguage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Codanux\MultiLanguage\Skeleton\SkeletonClass
 */
class MultiLanguageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'multi-language';
    }
}
