<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $medias = $this->getMedia('category_image');
        $mediaUrl = null;

        if (! empty($medias) && isset($medias[0]) && method_exists($medias[0], 'getFullUrl')) {
            $mediaUrl = $medias[0]->getFullUrl();
        }

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'media' => $mediaUrl,
        ];
    }
}
