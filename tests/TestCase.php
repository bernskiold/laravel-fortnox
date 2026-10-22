<?php

namespace BernskioldMedia\Fortnox\Tests;

use BernskioldMedia\Fortnox\FortnoxServiceProvider;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SocialiteServiceProvider::class,
            FortnoxServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
