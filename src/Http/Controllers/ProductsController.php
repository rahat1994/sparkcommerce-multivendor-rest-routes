<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;

class ProductsController extends Controller
{
    public function productRecomendation(Request $request, $productCount)
    {
        // validate productcount
        if (! is_numeric($productCount)) {
            return response()->json(['error' => 'Invalid product count'], 400);
        }

        $products = SCProduct::with('sCMVVendor', 'categories')
            ->limit($productCount)
            ->get();

        // $product = SCProduct::find(18);

        // dd($product->hasMedia('product_image')->collection('product_image')->first()->getUrl());

        // dd($products);
        return SCMVProductResource::collection($products);
    }
}
