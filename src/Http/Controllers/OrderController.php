<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCOrderResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $orders = SCOrder::limit(10)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return SCOrderResource::collection($orders);

        // return ShopCategoryResource::collection($topTenShopCategories);
    }
}
