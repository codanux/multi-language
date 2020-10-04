<?php

namespace Codanux\MultiLanguage;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

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
        // $this->loadRoutesFrom(__DIR__.'/routes.php');


        if ($this->app->runningInConsole()) {
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

            $this->publishes([
                __DIR__ . '/Models/Post/Post.php.stub' => app_path('Models/Post/Post.php'),
                __DIR__ . '/Models/Post/PostCategory.php.stub' => app_path('Models/Post/PostCategory.php'),
            ], 'models');

            if (! class_exists('CreatePostsTable')) {

                $this->publishes([
                    __DIR__ . '/../database/migrations/create_post_categories_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_post_categories_table.php'),
                    __DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
                    // you can add any number of migrations here
                ], 'migrations');
            }
        }
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
}
