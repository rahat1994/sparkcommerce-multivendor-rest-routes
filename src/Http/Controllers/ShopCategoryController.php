<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Http\Request;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;

class ShopCategoryController extends SCMVBaseController
{
    public $recordModel = SCMVShopCategory::class;

    public function index(Request $request)
    {
        try {

            $shopCategories = $this->recordModel::orderBy('order', 'desc')
                ->get();

            $modifiedShopCategories = $this->callHook('afterFetchingShopCategories', $shopCategories);

            $shopCategories = $modifiedShopCategories ?? $shopCategories;

            return $this->resourceCollection($shopCategories, $this->recordModel);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }

    }
}
