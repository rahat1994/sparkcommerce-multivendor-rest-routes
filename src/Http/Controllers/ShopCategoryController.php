<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;

class ShopCategoryController extends Controller
{
    public function index(Request $request)
    {

        $topTenShopCategories = SCMVShopCategory::limit(10)
            ->orderBy('order', 'desc')
            ->get();

        return ShopCategoryResource::collection($topTenShopCategories);
    }
}