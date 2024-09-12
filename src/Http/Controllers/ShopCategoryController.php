<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;

class ShopCategoryController extends SCMVBaseController
{
    public $recordModel = SCMVShopCategory::class;
    public function index(Request $request)
    {
        $shopCategories = $this->recordModel::orderBy('order', 'desc')
            ->get();

        $modifiedShopCategories = $this->callHook('afterFetchingShopCategories', $shopCategories);

        $shopCategories = $modifiedShopCategories ?? $shopCategories;
        return ShopCategoryResource::collection($shopCategories);
    }
}
