<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVOrderResource;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\ShopCategoryResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\SCBaseController;

class OrderController extends SCBaseController
{
    public function index(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $params = [
            'user_id' => $user->id,
            'limit' => 10,
            'order_by' => 'created_at',
            'order' => 'desc',
        ];

        $data = $this->callHook('orderParams', $params);

        $params = $data ?? $params;
        
        $orders = SCOrder::limit($params['limit'])
            ->where('user_id', $params['user_id'])
            ->orderBy($params['order_by'], $params['order'])
            ->paginate();

        $data = $this->callHook('orderList', $orders);

        $orders = $data ?? $orders;
        return SCMVOrderResource::collection($orders);
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
