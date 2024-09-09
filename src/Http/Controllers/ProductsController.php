<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
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

        return SCMVProductResource::collection($products);
    }

    public function searchProdcuts(Request $request, $vendor_slug, $search_term)
    {
        $vendor = SCMVVendor::where('slug', $vendor_slug)->first();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $products = $vendor->scproducts()
            ->where('name', 'like', '%'.$search_term.'%')
            ->with('sCMVVendor', 'categories')
            ->paginate(2);

        return SCMVProductResource::collection($products);
    }

    public function gloalSearch(Request $request)
    {
        $products = SCProduct::where('name', 'like', '%'.$request->search_term.'%')
            ->with('sCMVVendor', 'categories')
            ->paginate(10);

        return SCMVProductResource::collection($products);
    }

    public function products(Request $request, $vendor_slug)
    {
        // validate category query parameters
        $validatedData = $request->validate([
            'categories' => 'string',
        ]);
        // look for category query parameters
        if (isset($validatedData['categories'])) {

            // you will get categories as a string separated by comma
            $categories = explode(',', $request->categories);

            $vendor = SCMVVendor::where('slug', $vendor_slug)->first();

            if (! $vendor) {
                return response()->json(['message' => 'Vendor not found'], 404);
            }

            $products = $vendor->scproducts()
                ->whereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('slug', $categories);
                })
                ->with('sCMVVendor', 'categories')
                ->paginate(10);

            return SCMVProductResource::collection($products);
        }

        $vendor = SCMVVendor::where('slug', $vendor_slug)->first();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }
        $products = $vendor->scproducts()
            ->with('sCMVVendor', 'categories')
            ->paginate(10);

        return SCMVProductResource::collection($products);
        // dd($products);
        // return SC;
    }

    public function product(Request $request, $vendor_slug, $product_slug)
    {
        $vendor = SCMVVendor::where('slug', $vendor_slug)->first();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $product = $vendor->scproducts()
            ->where('slug', $product_slug)
            ->with('sCMVVendor', 'categories')
            ->first();

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return SCMVProductResource::make($product);
    }
}
