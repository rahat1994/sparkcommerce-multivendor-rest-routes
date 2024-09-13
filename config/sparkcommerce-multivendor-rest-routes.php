<?php

use Rahat1994\SparkCommerce\Models\SCCategory;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVAdvertisement;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVAdvertisementResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVOrderResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVShopCategoryResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVVendorResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCCategoryResource;

// config for Rahat1994/SparkcommerceMultivendorRestRoutes
return [
    'resource_mapping' => [
        SCProduct::class => SCMVProductResource::class,
        SCMVAdvertisement::class => SCMVAdvertisementResource::class,
        SCOrder::class => SCMVOrderResource::class,
        // SCMVPayoutRequest::class => SCMVAdvertisementResource::class,
        SCMVShopCategory::class => SCMVShopCategoryResource::class,
        SCMVVendor::class => SCMVVendorResource::class,
        SCCategory::class => SCCategoryResource::class,
    ],
];
