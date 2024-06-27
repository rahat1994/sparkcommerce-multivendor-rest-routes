<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SCMVVendorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'address' => 'Dhaka, Bangladesh',
            'media_urls' => $this->media_urls,
            'product_count' => $this->product_count,
        ];
    }
}
