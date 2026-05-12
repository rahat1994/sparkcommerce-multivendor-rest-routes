<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCOrderResource;

class SCMVOrderResource extends SCOrderResource
{
    public function toArray($request)
    {
        $orderResource = parent::toArray($request);

        return array_merge($orderResource, [
            'vendor' => SCMVVendorResource::make($this->sCMVVendor),
        ]);
    }
}
