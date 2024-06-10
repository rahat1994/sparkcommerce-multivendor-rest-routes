<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Commands\SparkcommerceMultivendorRestRoutesCommand;

class SparkcommerceMultivendorRestRoutesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sparkcommerce-multivendor-rest-routes')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('api')
            ->hasMigration('create_sparkcommerce-multivendor-rest-routes_table')
            ->hasCommand(SparkcommerceMultivendorRestRoutesCommand::class);
    }
}
