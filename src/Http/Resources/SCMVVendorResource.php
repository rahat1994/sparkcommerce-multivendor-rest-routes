<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class SCMVVendorResource extends JsonResource
{
    public function toArray($request)
    {
        // dd(json_decode($this->meta, true));
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'address' => $this->address,
            'media_urls' => $this->media_urls,
            'product_count' => $this->product_count,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'postcodes_covered' => Arr::get(json_decode($this->meta, true), 'postcodes', []),
            'delivery_days' => Arr::get(json_decode($this->meta, true), 'delivery_days', []),
        ];
    }
}
