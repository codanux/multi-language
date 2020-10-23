<?php

return [

    'locales' => [
        'en' => 'English',
        'tr' => 'Türkçe'
    ],

    'default_locale' => config('app.locale', 'en'),

    'default_prefix' => false,


    'router' => [
        'fortify' => [
            'routes' => false,
        ],

        'jetstream' => [
            'routes' => false,
            'stack' => config('jetstream.stack', 'livewire'),
        ],
    ],

    'middleware' => [
        'web' => [
            \Codanux\MultiLanguage\DetectRequestLocale::class
        ]
    ],

    'media' => [
        'translations_detect' => true, // translations all detect
        'locale' => 'en', // first look locale
        'media_repository' => 'Spatie\MediaLibrary\MediaCollections\MediaRepository',
    ],
];
