<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SCMVAdvertisementResource extends JsonResource
{
    public function toArray($request)
    {
        // dd($this->media->first());
        return [
            'name' => $this->name,
            'url' => $this->url,
            'position' => $this->position,
            'media_urls' => $this->media->map(function ($media) {
                return [
                    'url' => $media->getUrl(),
                    'collection' => $media->collection_name,
                ];
            }),
            
        ];
    }
}
