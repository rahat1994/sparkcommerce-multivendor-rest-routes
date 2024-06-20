<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVVendorResource;

class VendorController extends Controller
{
    public function topVendors(Request $request)
    {
        // $topVendors = SCMVVendor::whereJsonContains('meta->is_top_vendor', true)->get();
        $table = config('sparkcommerce-multivendor.table_prefix').'vendors';

        $topVendors = DB::table($table)->whereJsonContains('meta->is_top_vendor', 1)->get();

        return SCMVVendorResource::collection($topVendors);
    }
}
