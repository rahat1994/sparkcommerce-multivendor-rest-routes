<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCProductResource;

class ProductsController extends Controller
{
    public function productRecomendation(Request $request, $productCount)
    {
        // validate productcount
        if (! is_numeric($productCount)) {
            return response()->json(['error' => 'Invalid product count'], 400);
        }

        $productTable = config('sparkcommerce.table_prefix').'products';
        $products = DB::table($productTable)->inRandomOrder()->limit($productCount)->get();

        return SCProductResource::collection($products);
    }
}
