<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVVendorResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCCategoryResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VendorController extends Controller
{
    public function topVendors(Request $request)
    {
        $topVendors = SCMVVendor::whereJsonContains('meta->is_top_vendor', 1)
        ->with('media', 'sCProducts')
        ->get();
        return SCMVVendorResource::collection($topVendors);
    }

    public function show(Request $request, $vendor_slug)
    {
        $vendor = SCMVVendor::where('slug', $vendor_slug)->first();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        return new SCMVVendorResource($vendor);
    }

    public function categories(Request $request, $vendor_slug)
    {
        $vendor = SCMVVendor::where('slug', $vendor_slug)->first();
        if (! $vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }
        $categories = $vendor->sccategories()->with('childrenRecursive')->whereNull('parent_id')->get();

        return SCCategoryResource::collection($categories);
    }
}
