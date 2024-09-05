<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVOrderResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $orders = SCOrder::limit(10)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return SCMVOrderResource::collection($orders);

        // return ShopCategoryResource::collection($topTenShopCategories);
    }

    public function show(Request $request, $trackingNumber)
    {
        $user = Auth::guard('sanctum')->user();

        try {
            $order = SCOrder::where('tracking_number', $trackingNumber)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return SCMVOrderResource::make($order);
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

    public function cancelOrder($trackingNumber)
    {
        // $order = SCOrder::where('tracking_number', $trackingNumber)
        // ->where('user_id', $user->id)
        // ->firstOrFail();
    }

    public function updateOrder($trackingNumber)
    {
        // $order = SCOrder::where('tracking_number', $trackingNumber)
        // ->where('user_id', $user->id)
        // ->firstOrFail();
    }
}