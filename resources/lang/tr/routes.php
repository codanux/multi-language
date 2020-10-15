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
        'show' => 'kullanici/profil',
    ],
    'api-tokens' => [
        'index' => 'kullanici/api-tokens',
        'create' => 'kullanici/api-tokens',
        'update' => 'kullanici/api-tokens/{token}',
        'destroy' => 'kullanici/api-tokens/{token}',
    ],
    'teams' => [
        'create' => 'takim/yeni',
        'show' => 'takim/{team}',
        'edit' => 'takim/{team}',
        'destroy' => 'takim/{team}',
    ],

    'team-members' => [
        'create' => 'takim/{team}/uyeler',
        'edit' => 'takim/{team}/uyeler/{user}',
        'destroy' => 'takim/{team}/uyeler/{user}',
    ],

    'current-team' => [
        'edit' => 'oldugun-takim',
    ],

    'other-browser-sessions' => [
        'destroy' => 'kullanici/diğer-browser-sessions',
    ],

    'current-user' => [
        'destroy' => 'kullanici'
    ],

    'current-user-photo' => [
        'destroy' => 'kullanici/profile-photo',
    ],

    'post' => [
        'index' => 'postlar',
        'create' => 'postlar/yeni',
        'show' => 'postlar/{post}',
        'edit' => 'postlar/{post}/duzenle',
        'destroy' => 'postlar/{post}/sil',
    ],
];
