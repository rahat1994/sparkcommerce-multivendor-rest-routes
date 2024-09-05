<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $medias = $this->getMedia('category_image');

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'media' => $medias[0]->getFullUrl(),
        ];
    }
}
