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
            ->paginate(10);

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
        // Validate category query parameters
        $validatedData = $request->validate([
            'categories' => 'nullable|string',
            'search' => 'nullable|string',
        ]);

        // Find the vendor by slug
        $vendor = SCMVVendor::where('slug', $vendor_slug)->firstOrFail();

        // Initialize the query builder for products
        $query = SCProduct::where('vendor_id', $vendor->id)
            ->with('sCMVVendor', 'categories');

        // Filter by categories if provided
        if (isset($validatedData['categories'])) {
            $categories = explode(',', $validatedData['categories']);
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Search by product name or description if provided
        if (isset($validatedData['search'])) {
            $search = $validatedData['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        // Paginate the results
        $products = $query->paginate(10);

        // Return the products as a resource collection
        return SCMVProductResource::collection($products);
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
