<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rahat1994\SparkcommerceMultivendorRestRoutes\SparkcommerceMultivendorRestRoutes
 */
class SparkcommerceMultivendorRestRoutes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Rahat1994\SparkcommerceMultivendorRestRoutes\SparkcommerceMultivendorRestRoutes::class;
    }
}
