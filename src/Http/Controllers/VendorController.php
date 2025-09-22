<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Rahat1994\SparkCommerce\Models\SCCategory;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Illuminate\Support\Arr;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Concerns\CanInteractWithVendors;
class VendorController extends SCMVBaseController
{
    use CanInteractWithVendors;
    public $recordModel = SCMVVendor::class;

    public function topVendors(Request $request)
    {
        try {
            $topVendors = $this->recordModel::whereJsonContains('meta->is_top_vendor', 1)
                ->with('media', 'sCProducts')
                ->get();

            $topVendors = $this->removeDisabledVendors($topVendors);

            $modifiedTopVendors = $this->callHook('afterFetchingTopVendors', $topVendors);
            $topVendors = $modifiedTopVendors ?? $topVendors;

            return $this->resourceCollection($topVendors);
        } catch (ModelNotFoundException $th) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $vendors = $this->recordModel::with('media')
                ->get();
            $vendors = $this->removeDisabledVendors($vendors);
            $modifiedVendors = $this->callHook('afterFetchingVendors', $vendors);
            $vendors = $modifiedVendors ?? $vendors;

            return $this->resourceCollection($vendors);
        } catch (ModelNotFoundException $th) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function search(Request $request, $category = null)
    {
        try {
            $validator = validator()->make(['category' => $category], [
                'category' => 'nullable|string',
            ]);
            $data = $validator->validate();
            $category = $data['category'];

            $vendors = $this->recordModel::with('media', 'sCProducts')
                ->when($category, function ($query, $category) {
                    return $query->where('category', 'like', '%"'.$category.'"%');
                })
                ->get();

            $vendors = $this->removeDisabledVendors($vendors);

            $modifiedVendors = $this->callHook('afterFetchingSearchVendors', $vendors);
            $vendors = $modifiedVendors ?? $vendors;

            return $this->resourceCollection($vendors);
        } catch (ModelNotFoundException $th) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function show(Request $request, $vendor_slug)
    {
        try {
            $vendor = $this->recordModel::where('slug', $vendor_slug)
                ->with('media', 'sccategories')
                ->firstOrFail();
            $vendor = $this->removeDisabledVendors(collect([$vendor]))->first();
            if (!$vendor) {
                return response()->json(['message' => 'Vendor not found'], 404);
            }

            $modifiedVendor = $this->callHook('afterFetchingSingleVendor', $vendor);
            $vendor = $modifiedVendor ?? $vendor;

            return $this->singleModelResource($vendor);
        } catch (ModelNotFoundException $th) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function categories(Request $request, $vendor_slug)
    {
        try {
            $vendor = $this->getRecordBySlug($vendor_slug);

            $vendor = $this->removeDisabledVendors(collect([$vendor]))->first();
            if (!$vendor) {
                return response()->json(['message' => 'Vendor not found'], 404);
            }

            $categories = $vendor->sccategories()->with('childrenRecursive')->whereNull('parent_id')->get();

            $modifiedCategories = $this->callHook('afterFetchingVendorCategories', $categories);
            $categories = $modifiedCategories ?? $categories;

            return $this->resourceCollection($categories, SCCategory::class);
        } catch (ModelNotFoundException $th) {
            // TODO: Improve the message and Localization
            return response()->json(['message' => 'Vendor not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
