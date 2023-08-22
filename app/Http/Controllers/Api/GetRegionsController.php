<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Subregion;

class GetRegionsController extends Controller
{
    public function getSubRegions($id)
    {
        $data = Subregion::where('region_id', $id)->get();
        if (!isset($data) || is_null($data)) {
            return response()->json(['error' => 'No Subregions found']);
        }
        return response()->json(['data' => Subregion::where('region_id', $id)->get()]);
    }
    public function getAreas($id)
    {
        $data = Area::where('subregion_id', $id)->get();
        if (!isset($data) || is_null($data)) {
            return response()->json(['error' => 'No Subregions found']);
        }
        return response()->json(['data' => Area::where('subregion_id', $id)->get()]);
    }
}
