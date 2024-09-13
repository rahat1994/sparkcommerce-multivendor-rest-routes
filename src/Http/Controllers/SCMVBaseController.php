<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\SCBaseController;

class SCMVBaseController extends SCBaseController
{
    protected function getResourceClassMapping(): array
    {
        // TODO: move this array to config files
        return config('sparkcommerce-multivendor-rest-routes.resource_mapping');
    }
}
