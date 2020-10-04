<?php

return [
    'welcome' => '', // default_prefix => false
    'home' => 'dashboard',


    'post' => [
        'index' => 'posts',
        'create' => 'posts/create',
        'store' => 'posts/create',
        'show' => 'posts/{post}',
        'edit' => 'posts/{post}/edit',
        'update' => 'posts/{post}/edit',
        'destroy' => 'posts/{post}/delete',
    ],
];
