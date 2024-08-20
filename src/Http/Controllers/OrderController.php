<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVShopCategory;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCOrderResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $orders = SCOrder::limit(10)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return SCOrderResource::collection($orders);

        // return ShopCategoryResource::collection($topTenShopCategories);
    }

    public function show(Request $request, $trackingNumber){
        $user = Auth::guard('sanctum')->user();

        try {
            $order = SCOrder::where('tracking_number', $trackingNumber)
                ->where('user_id', $user->id)
                ->firstOrFail();    

            // dd($order);
            return SCOrderResource::make($order);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'message' => 'Order not found',
                    'tracking_number' => $trackingNumber,
                ],
                404
            );
        }
        

    }
}
