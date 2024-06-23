<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVAdvertisementResource;

class AdvertisementController extends Controller
{
    public function advertisements(Request $request)
    {
        $advertiementTable = config('sparkcommerce-multivendor.table_prefix').'advertisements';

        // Subquery to get the latest entry for each position
        $latestPerPosition = DB::table($advertiementTable)
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('position');

        // Main query to get the advertisement details for the latest entries per position
        $advertisements = DB::table($advertiementTable.' as main')
            ->joinSub($latestPerPosition, 'latest', function ($join) {
                $join->on('main.id', '=', 'latest.id');
            })
            ->get();

        return SCMVAdvertisementResource::collection($advertisements);
    }
}
