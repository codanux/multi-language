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

    'links' => [
        'li' => [
            'active_class' => 'uk-active',
            'inactive_class' => '',
            'class' => 'li'
        ],
        'a' => [
            'class' => 'nav-link'
        ]
    ]

];
