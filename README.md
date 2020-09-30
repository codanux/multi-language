# Very short description of the package

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

To detect and change the locale of the application based on the request automatically, you can add the middleware to your `app/Http/Kernel`:

``` php
protected $middlewareGroups = [
    'web' => [
        \Codanux\MultiLanguage\DetectRequestLocale::class,
        // ...
    ]
];
```

## Usage

``` php
## Model

class Post extends Model
{
    use HasLanguage;
    use HasMedia; // "spatie/laravel-medialibrary": "^8.0.0"
}

## Database

Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->locale(); // added
});

## Route

Route::localeResource('post', 'PostController')->names('post');

Route::locale('get', 'post.index', 'PostController@index');


## Controller

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
       'translation_of' => $post->translation_of
    ]);
}

public function show(Post $post)
{
    return view('post.show', compact('post'));
}

## Views

post.index
    @include("multi-language::links")

post.show
     @include("multi-language::links", ['translations' => ['post' => $post]])
    // if category with translations ['category' => $category, 'post => $post]


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
