<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class SCMVVendorResource extends JsonResource
{
    public function toArray($request)
    {
        // dd(json_decode($this->meta, true));

        if (! is_array($this->meta)) {
            $meta = json_decode($this->meta, true);
        } else {
            $meta = $this->meta;
        }

        $postcodes_covered = Arr::get($meta, 'postcodes', []);
        $delivery_days = Arr::get($meta, 'delivery_days', []);

        $media_urls = [];

        foreach ($this->media as $media) {
            $media_urls[] = [
                'collection' => $media->collection_name,
                'url' => $media->getUrl(),
            ];
        }

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'address' => $this->address,
            'media_urls' => $media_urls,
            'product_count' => $this->sCProducts->count(),
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'postcodes_covered' => $postcodes_covered,
            'delivery_days' => $delivery_days,
            'minimum_order_amount' => '100',
        ];
    }
}
