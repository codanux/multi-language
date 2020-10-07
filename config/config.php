<?php

/*
 * You can place your custom package configuration in here.
 */

return [
    'locales' => [
        'en',
        'tr'
    ],

    'default_locale' => config('app.locale', 'en'),

    'default_prefix' => false,

    'jetstream' => [
        'routes' => false,
        'stack' => config('jetstream.stack', 'livewire'),
    ],


    'media' => [
        'translations_detect' => true, // translations all detect
        'locale' => 'en', // first look locale
        'media_repository' => 'Spatie\MediaLibrary\MediaCollections\MediaRepository',
    ],

    'links' => [
        'li' => [
            'active_class' => 'uk-active',
            'inactive_class' => '',
            'class' => 'li'
        ],
        'a' => [
            'class' => 'nav-link'
        ]
    ],

];
