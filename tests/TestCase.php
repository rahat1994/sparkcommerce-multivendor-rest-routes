<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Rahat1994\SparkcommerceMultivendorRestRoutes\SparkcommerceMultivendorRestRoutesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Rahat1994\\SparkcommerceMultivendorRestRoutes\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SparkcommerceMultivendorRestRoutesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_sparkcommerce-multivendor-rest-routes_table.php.stub';
        $migration->up();
        */
    }
}
