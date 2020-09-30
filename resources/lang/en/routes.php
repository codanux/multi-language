<?php

return [
    'post' => [
        'index' => 'posts',
        'create' => 'posts/create',
        'store' => 'posts/create',
        'show' => 'post/{post}',
        'edit' => 'post/{post}/edit',
        'update' => 'post/{post}/edit',
        'destroy' => 'post/{post}/delete',
    ],
];
