<?php

namespace Codanux\MultiLanguage;

use Codanux\MultiLanguage\Macros\RedirectMacros;
use Codanux\MultiLanguage\Macros\RequestMacros;
use Codanux\MultiLanguage\Macros\RouterMacros;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MultiLanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'multi-language');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'multi-language');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if (class_exists(\Laravel\Fortify\Fortify::class)) {

            if (config('multi-language.router.fortify.routes', false))
            {
                $this->loadRoutesFrom(__DIR__.'/../routes/fortify.php');
            }

            if (config('multi-language.router.jetstream.routes', false))
            {
                $this->loadRoutesFrom(__DIR__.'/../routes/'.config('multi-language.router.jetstream.stack', 'livewire').'.php');
            }
        }

        $this->configureComponents();
        $this->configurePublishing();
        $this->bootMiddleware();
    }

    /**
     * Register the application services.
     * @throws \ReflectionException
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'multi-language');

        // Register the main class to use with the facade
        $this->app->singleton('multi-language', function () {
            return new MultiLanguage;
        });

        Router::mixin(new RouterMacros);

        Request::mixin(new RequestMacros);

        Redirect::mixin(new RedirectMacros);

        require __DIR__.'/helpers.php';

        Blueprint::macro('locale', function () {
            $this->string('locale');
            $this->string('locale_slug')->nullable();
            $this->uuid('translation_of');
            $this->unique(['locale', 'translation_of']);
        });
    }

    protected function configurePublishing()
    {
        if ($this->app->runningInConsole())
        {
            if (class_exists(\Laravel\Fortify\Fortify::class))
            {
                $this->publishes([
                    __DIR__.'/../routes/fortify.php' => base_path('routes/multi-language/fortify.php'),
                ], 'multi-language-fortify');

                if ($stack = config('multi-language.router.jetstream.stack', false))
                {
                    $this->publishes([
                        __DIR__.'/../routes/'.config('multi-language.router.jetstream.stack').'.php' => base_path('routes/multi-language/jetstream.php'),
                    ], 'multi-language-jetstream');
                }
            }

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('multi-language.php'),
            ], 'multi-language-config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/multi-language'),
            ], 'multi-language-views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/multi-language'),
            ], 'assets');*/

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang'),
            ], 'multi-language-lang');


            // if (! class_exists('CreatePostsTable')) {
            //    $this->publishes([
            //        __DIR__ . '/Models/Post/Post.php.stub' => app_path('Models/Post/Post.php'),
            //        __DIR__ . '/Models/Post/PostCategory.php.stub' => app_path('Models/Post/PostCategory.php'),
            //    ], 'multi-language-models');
            //    $this->publishes([
            //        __DIR__ . '/../database/migrations/create_post_categories_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_post_categories_table.php'),
            //        __DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
            //    ], 'multi-language-migrations');
            // }
        }
    }

    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('multi-language::components.breadcrumb', 'breadcrumb');
            Blade::component('multi-language::components.link', 'link');
            Blade::component('multi-language::components.links', 'links');
        });
    }

    protected function bootMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);

        foreach (config('multi-language.middleware', []) as $key => $middlewares)
        {
            foreach ($middlewares as $middleware)
            {
                $kernel->appendMiddlewareToGroup($key, $middleware);
            }
        }
    }
}
