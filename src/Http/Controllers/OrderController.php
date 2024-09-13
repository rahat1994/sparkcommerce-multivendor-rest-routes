<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\OrderController as SCOrderController;

class OrderController extends SCOrderController
{
    public $recordModel = SCOrder::class;

    protected function orderIndexQueryBuilder($orders)
    {
        return $orders->with('sCMVVendor');
    }

    protected function getResourceClassMapping(): array
    {
        return config('sparkcommerce-multivendor-rest-routes.resource_mapping');
    }
}
