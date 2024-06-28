<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCProductResource;

class SCMVProductResource extends SCProductResource
{
    public function toArray($request)
    {
        $productResource = parent::toArray($request);

        return array_merge($productResource, [
            'vendor' => SCMVVendorResource::make($this->sCMVVendor) ,
        ]);
    }
}
