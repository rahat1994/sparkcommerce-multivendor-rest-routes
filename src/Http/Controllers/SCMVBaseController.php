<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVAdvertisement;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVAdvertisementResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVOrderResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVShopCategoryResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\SCBaseController;

class SCMVBaseController extends SCBaseController {

    protected function getResourceClassMapping(): array
    {
        // TODO: move this array to config files
        return config('sparkcommerce-multivendor-rest-routes.resource_mapping');
    }
}
