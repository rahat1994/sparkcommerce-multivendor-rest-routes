<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVVendorResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VendorController extends Controller
{
    public function topVendors(Request $request)
    {
        // $topVendors = SCMVVendor::whereJsonContains('meta->is_top_vendor', true)->get();
        $table = config('sparkcommerce-multivendor.table_prefix').'vendors';

        $topVendors = DB::table($table)->whereJsonContains('meta->is_top_vendor', 1)->get();

        // Assuming you have the model instances, but since you're using DB::table, you'll manually fetch media
        $vendorIds = $topVendors->pluck('id')->toArray();
        $modelType = SCMVVendor::class; // Fully qualified class name of the model
        // Fetch media for these vendors
        $mediaItems = Media::whereIn('model_id', $vendorIds)
            ->where('model_type', $modelType)
            ->get();

        $vendorProducts = DB::table(config('sparkcommerce.table_prefix').'products')
            ->whereIn('vendor_id', $vendorIds)
            ->get();
        // Group media items by model_id for easy assignment
        $mediaByVendor = $mediaItems->groupBy('model_id');
        $vendorProducts = $vendorProducts->groupBy('vendor_id');

        // Manually attach media items to each vendor
        $topVendors->transform(function ($vendor) use ($mediaByVendor) {
            $vendorMedia = $mediaByVendor[$vendor->id] ?? collect();
            $vendorProduct = $vendorProducts[$vendor->id] ?? collect();
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

            $vendor->product_count = count($vendorProduct);

            return $vendor;
        });

        return SCMVVendorResource::collection($topVendors);
    }
}
