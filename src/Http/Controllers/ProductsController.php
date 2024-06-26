<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCProductResource;

class ProductsController extends Controller
{
    public function productRecomendation(Request $request, $productCount)
    {
        // validate productcount
        if (! is_numeric($productCount)) {
            return response()->json(['error' => 'Invalid product count'], 400);
        }

        $products = SCProduct::with('sCMVVendor')
            ->inRandomOrder()
            ->limit($productCount)
            ->get();
        // dd($products)  ;
        return SCMVProductResource::collection($products);
    }
}
