<?php

namespace Codanux\MultiLanguage\Tests;

use Illuminate\Support\Facades\Route;

use Orchestra\Testbench\TestCase;
use Codanux\MultiLanguage\MultiLanguageServiceProvider;

class ExampleTest extends TestCase
{
    /** @test **/
    public function a_multilingual_route_can_be_registered(): void
    {
        $this->registerTranslations([
            'en' => [
                'routes.welcome' => 'welcome',
            ],
            'tr' => [
                'routes.welcome' => 'hosgeldin',
            ],
        ]);

        Route::locale('get', 'welcome', static function () {
            return "welcome";
        });

        $this->assertEquals(url('welcome'), routeLocalized('welcome'));
    }


    protected function registerTranslations(array $translations): self
    {
        $translator = app('translator');

        foreach ($translations as $locale => $translation) {
            $translator->addLines($translation, $locale);
        }

        return $this;
    }

    protected function getPackageProviders($app)
    {
        return [MultiLanguageServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
