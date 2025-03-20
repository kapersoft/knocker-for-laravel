<?php

namespace Kapersoft\Knocker\Tests;

use Kapersoft\Knocker\KnockerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [KnockerServiceProvider::class];
    }
}
