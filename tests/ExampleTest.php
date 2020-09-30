<?php

namespace Codanux\MultiLanguage\Tests;

use Orchestra\Testbench\TestCase;
use Codanux\MultiLanguage\MultiLanguageServiceProvider;

class ExampleTest extends TestCase
{

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
