<?php

namespace Codanux\MultiLanguage;

use Closure;
use Codanux\MultiLanguage\View\Components\LinksComponent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Component;

class MultiLanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Router::mixin(new RouterMacros);

        require __DIR__.'/helpers.php';

        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'multi-language');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'multi-language');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if (class_exists(\Laravel\Fortify\Fortify::class) && config('multi-language.jetstream.routes', false))
        {
            $this->loadRoutesFrom(__DIR__.'/../routes/fortify.php');

            if ($stack = config('multi-language.jetstream.stack', false))
            {
                $this->loadRoutesFrom(__DIR__.'/../routes/'.$stack.'.php');
            }
        }

        $this->configureComponents();
        $this->configurePublishing();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'multi-language');

        // Register the main class to use with the facade
        $this->app->singleton('multi-language', function () {
            return new MultiLanguage;
        });


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
                if ($stack = config('multi-language.jetstream.stack', false))
                {
                    $this->publishes([
                        __DIR__.'/../routes/'.config('jetstream.stack').'.php' => base_path('routes/multi-language/jetstream.php'),
                    ], 'jetstream-routes');
                }
            }

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('multi-language.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/multi-language'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/multi-language'),
            ], 'assets');*/

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang'),
            ], 'lang');

            // Registering package commands.
            // $this->commands([]);

            if (! class_exists('CreatePostsTable')) {

                $this->publishes([
                    __DIR__ . '/Models/Post/Post.php.stub' => app_path('Models/Post/Post.php'),
                    __DIR__ . '/Models/Post/PostCategory.php.stub' => app_path('Models/Post/PostCategory.php'),
                ], 'models');


                $this->publishes([
                    __DIR__ . '/../database/migrations/create_post_categories_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_post_categories_table.php'),
                    __DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
                    // you can add any number of migrations here
                ], 'migrations');
            }
        }
    }

    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('locale-links', LinksComponent::class);
        });
    }
}
