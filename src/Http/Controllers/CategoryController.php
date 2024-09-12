<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Http\Request;
use Rahat1994\SparkCommerce\Models\SCCategory;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCCategoryResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\CategoryController as SCCategoryController;
class CategoryController extends SCCategoryController
{
    public function categoriesByVendorId(Request $request, $vendor_id)
    {
        $categories = SCCategory::where('vendor_id', $vendor_id)
            ->with('childrenRecursive')
            ->whereNull('parent_id')
            ->get();
        return SCCategoryResource::collection($categories);
    }

    public function show($slug)
    {
        return 'Category Show';
    }
}
