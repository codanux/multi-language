<?php

return [

    'locales' => [
        'en',
        'tr'
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


    'media' => [
        'translations_detect' => true, // translations all detect
        'locale' => 'en', // first look locale
        'media_repository' => 'Spatie\MediaLibrary\MediaCollections\MediaRepository',
    ],
];
