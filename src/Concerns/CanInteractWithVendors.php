<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Concerns;
use Illuminate\Support\Arr;

trait CanInteractWithVendors
{
    public function removeDisabledVendors($vendors)
    {
        return $vendors->filter(function ($vendor) {
            $vendorDisabled = Arr::get($vendor->meta, 'disable_vendor', 0);
            return !$vendorDisabled;
        })->values();
    }
}
