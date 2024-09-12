<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;

class ProductsController extends SCMVBaseController
{
    public $recordModel = SCProduct::class;

    public function productRecomendation(Request $request, $productCount)
    {
        // validate productcount
        if (! is_numeric($productCount)) {
            return response()->json(['error' => 'Invalid product count'], 400);
        }

        $products = $this->recordModel::with('sCMVVendor', 'categories')
            ->limit($productCount)
            ->get();

        $modifiedProducts = $this->callHook('afterFetchingProductRecommendation', $products);

        $products = $modifiedProducts ?? $products;

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

        $modifiedProducts = $this->callHook('afterFetchingSearchProdcuts', $products);

        $products = $modifiedProducts ?? $products;

        return SCMVProductResource::collection($products);
    }

    public function gloalSearch(Request $request)
    {
        $products = $this->recordModel::where('name', 'like', '%'.$request->search_term.'%')
            ->with('sCMVVendor', 'categories')
            ->paginate(10);

        $modifiedProducts = $this->callHook('afterFetchingGloalSearch', $products);

        $products = $modifiedProducts ?? $products;

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
        $query = $this->recordModel::where('vendor_id', $vendor->id)
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

        $modifiedProducts = $this->callHook('afterFetchingProductsSearch', $products);

        $products = $modifiedProducts ?? $products;

        // Return the products as a resource collection
        return SCMVProductResource::collection($products);
    }

    public function product(Request $request, $vendor_slug, $product_slug)
    {
        try {
            $vendor = $this->getRecordBySlug($vendor_slug, SCMVVendor::class);

            if (! $vendor) {
                return response()->json(['message' => 'Vendor not found'], 404);
            }

            $product = $vendor->scproducts()
                ->where('slug', $product_slug)
                ->with('sCMVVendor', 'categories')
                ->firstOrFail();

            $modifiedProduct = $this->callHook('afterFetchingProduct', $product);

            $product = $modifiedProduct ?? $product;

            return SCMVProductResource::make($product);
        } catch (ModelNotFoundException $exception) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Throwable $th) {
            //throw $th;
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
