<?php

namespace Codanux\MultiLanguage\Tests;

use Illuminate\Support\Facades\Route;

use Orchestra\Testbench\TestCase;
use Codanux\MultiLanguage\MultiLanguageServiceProvider;

class ExampleTest extends TestCase
{

    /** @test **/
    public function route_can_registered(): void
    {
        $this->registerConfigs([
            'multi-language.default_prefix' => true
        ]);

        $this->registerTranslations([
            'en' => [
                'routes.dashboard' => 'dashboard',
            ],
            'tr' => [
                'routes.dashboard' => 'panel',
            ],
        ]);

        Route::locale('dashboard', function () {
            return "dashboard";
        });

        $this->assertEquals(url('en/dashboard'), routeLocalized('dashboard', [], 'en'));

        $this->assertEquals(url('tr/panel'), routeLocalized('dashboard', [], 'tr'));

    }

    protected function registerTranslations(array $translations): self
    {
        $translator = app('translator');

        foreach ($translations as $locale => $translation) {
            $translator->addLines($translation, $locale);
        }

        return $this;
    }

    protected function registerConfigs(array $configs): self
    {
        $config = app('config');

        foreach ($configs as $key => $value) {
            $config->set($key, $value);
        }

        return $this;
    }

    protected function getPackageProviders($app)
    {
        return [MultiLanguageServiceProvider::class];
    }
}
