<?php

return [
    'welcome' => '',
    'dashboard' => 'panel',

    'login' => 'giris',
    'logout' => 'cikis',
    'register' => 'kayit',

    'password' => [
        'request' => 'sifre/sifirla',
        'email' => 'sifre/e-posta',
        'reset' => 'sifre/sifirla/{token}',
        'update' => 'sifre/sifirla',
        'confirm' => 'sifre/onayla',
        'confirmation' => 'kullanici/sifre-dogrulama-durumu',
    ],

    'verification' => [
        'notice' => 'e-posta/dogrula',
        'verify' => 'e-posta/dogrula/{id}/{hash}',
        'resend' => 'e-posta/yeniden-gonder',
    ],

    'two-factor' => [
        'login' => 'iki-adimli-dogrulama'
    ],

    'profile' => [
        'show' => '/user/profile',
    ],
    'api-tokens' => [
        'index' => '/user/api-tokens',
        'store' => '/user/api-tokens',
        'update' => '/user/api-tokens/{token}',
        'destroy' => '/user/api-tokens/{token}',
    ],
    'teams' => [
        'create' => '/teams/create',
        'store' => '/teams',
        'show' => '/teams/{team}',
        'update' => '/teams/{team}',
        'destroy' => '/teams/{team}',
    ],

    'team-members' => [
        'store' => '/teams/{team}/members',
        'update' => '/teams/{team}/members/{user}',
        'destroy' => '/teams/{team}/members/{user}',
    ],

    'current-team' => [
        'update' => '/current-team',
    ],

    'other-browser-sessions' => [
        'destroy' => '/user/other-browser-sessions',
    ],

    'current-user' => [
        'destroy' => 'user'
    ],

    'current-user-photo' => [
        'destroy' => '/user/profile-photo',

    ],


    'post' => [
        'index' => 'postlar',
        'create' => 'postlar/yeni',
        'show' => 'postlar/{post}',
        'edit' => 'postlar/{post}/duzenle',
        'destroy' => 'postlar/{post}/sil',
    ],
];
