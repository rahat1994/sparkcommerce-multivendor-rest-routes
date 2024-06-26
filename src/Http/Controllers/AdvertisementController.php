<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVAdvertisement;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVAdvertisementResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

        $advertisementIds = $advertisements->pluck('id')->toArray();
        $modelType = SCMVAdvertisement::class; // Fully qualified class name of the model

        // Fetch media for these vendors
        $mediaItems = Media::whereIn('model_id', $advertisementIds)
            ->where('model_type', $modelType)
            ->get();
        // Group media items by model_id for easy assignment
        $mediaByAdvertisement = $mediaItems->groupBy('model_id');

        // Manually attach media items to each vendor
        $advertisements->transform(function ($vendor) use ($mediaByAdvertisement) {
            $vendorMedia = $mediaByAdvertisement[$vendor->id] ?? collect();
            // if (!$vendorMedia->isEmpty()) {
            //     dd($vendorMedia);
            // }

            // Assuming you want to add the media as a simple array of URLs
            $vendor->media_urls = $vendorMedia->map(function (Media $record) {
                return [
                    'url' => $record->getUrl(),
                    'collection' => $record->collection_name,
                ];
            })->toArray();

            return $vendor;
        });

        return SCMVAdvertisementResource::collection($advertisements);
    }
}
