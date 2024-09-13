<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVAdvertisement;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVAdvertisementResource;

class AdvertisementController extends SCMVBaseController
{
    public $recordModel = SCMVAdvertisement::class;
    public function advertisements(Request $request)
    {   
        try {
            $latestPerPosition = $this->recordModel::select(DB::raw('MAX(id) as id'))
            ->groupBy('position');

            $modifiedLatestPerPosition = $this->callHook('latestPerPosition', $latestPerPosition);

            $latestPerPosition = $modifiedLatestPerPosition ?? $latestPerPosition;

            $advertisements = $this->recordModel::whereIn('id', $latestPerPosition->pluck('id'))
                ->with('media')
                ->get();    
                
            $modifiedAdvertisements = $this->callHook('asfterFetchingadvertisements', $advertisements);
            
            $advertisements = $modifiedAdvertisements ?? $advertisements;
            return $this->resourceCollection($advertisements);
        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => "Resource Not found"], 404);
        }        
        catch (\Throwable $th) {
            return response()->json(['message' => "Something went wrong."], 500);
        }     
    }
}
