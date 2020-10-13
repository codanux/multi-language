# Laravel Multi Language Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codanux/multi-language.svg?style=flat-square)](https://packagist.org/packages/codanux/multi-language)
[![Build Status](https://img.shields.io/travis/codanux/multi-language/master.svg?style=flat-square)](https://travis-ci.org/codanux/multi-language)
[![Quality Score](https://img.shields.io/scrutinizer/g/codanux/multi-language.svg?style=flat-square)](https://scrutinizer-ci.com/g/codanux/multi-language)
[![Total Downloads](https://img.shields.io/packagist/dt/codanux/multi-language.svg?style=flat-square)](https://packagist.org/packages/codanux/multi-language)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require codanux/multi-language
```

## Configuration


``` php
php artisan vendor:publish --provider="Codanux\MultiLanguage\MultiLanguageServiceProvider"
```

To detect and change the locale of the application based on the request automatically:

``` php
config/multi-language.php

'middleware' => [
    'web' => [
        \Codanux\MultiLanguage\DetectRequestLocale::class
    ]
],
```

## Model

``` php
class Post extends Model // implements HasMedia
{
    use HasLanguage;
    // use InteractsWithMedia, MediaTrait { MediaTrait::getMedia insteadof InteractsWithMedia; }
}
```

## Migration
``` php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->locale(); // added
});
```

## Route
``` php
Route::localeResource('post', 'PostController')->names('post');
or
Route::locale('post.index', 'PostController@index');
Route::locale('post.show', 'PostController@show');
Route::locale('post.create', 'PostController@create');
Route::locale('post.create', 'PostController@store')->method('POST');
Route::locale('post.edit', 'PostController@edit');
Route::locale('post.edit', 'PostController@update')->method('PUT');
Route::locale('post.destroy', 'PostController@destroy')->method('DELETE');


Route::locale('dashboard', function () {
    return view('dashboard');
})
->name('home'); // default dashboard name


Route::group(['localePrefix' => 'admin.prefix'], function (){

    Route::locale('dashboard', function () {
        return view('admin.dashboard');
    })
    ->name('admin.dashboard');

});
```

## Route Usage

``` php
routeLocalized('post.show', $post)

| Method    | URI           | Name          | Action                              |
|-----------|---------------|---------------|-------------------------------------|
| GET\|HEAD | posts/{post}  | en.post.show  | App\Http\Controllers\PostController@show |
| GET\|HEAD | tr/postlar/{post} | tr.post.show | App\Http\Controllers\PostController@show |
```

## Controller
``` php
public function index()
{
    // locale scope
    $posts = (new Post())->newQuery()->locale()->get();
}

public function store(Request $request)
{
    $post = Post::create([
       'name' => 'Post en',
       'locale' => 'en',
   ]);

    Post::create([
       'name' => 'Post tr',
       'locale' => 'tr',
       'locale_slug' => 'post-1',
       'translation_of' => $post->translation_of
    ]);
    
    Post::localeSlug('post-1', 'tr')->first() // Post tr
    
    Post::localeSlug('post-1', 'en')->first() // Post en
}

public function show(Post $post)
{
    return view('post.show', compact('post'));
}
```

## Views
``` php
post.index
    <x-locale-links component="jet-nav-link"></x-locale-links>

post.show
    <x-locale-links :translations=['post' => $post]></x-locale-links>
    
    //category/{category}/posts/{post}

    // route model translations ['category' => $category, 'post => $post]
```

## Jetstream Router

``` php
1. config/multi-language.php

'fortify' => [
    'routes' => true,
],

'jetstream' => [
    'routes' => true,
]

2. Default Router İgnore

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        Fortify::ignoreRoutes();
    }
}

class JetstreamServiceProvider extends ServiceProvider
{
   public function register()
   {
       Jetstream::ignoreRoutes();
   }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email codanux@hotmail.com instead of using the issue tracker.

## Credits

- [Ömer](https://github.com/codanux)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
