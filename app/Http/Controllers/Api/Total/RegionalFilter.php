<?php

namespace App\Http\Controllers\Api\total;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\customers;
use App\Models\Region;
use App\Models\Subarea;
use App\Models\Subregion;
use App\Models\zone;
use Illuminate\Http\Request;

class RegionalFilter extends Controller
{

   /**
    *

    *
    * @param Request $request
    * @return void
    */
   public function filterRegionalCustomers(Request $request)
   {
      $route_code = $request->user()->route_code;
      $data = array();
      $regions = Region::whereId($route_code)->first();
      $subregion = Subregion::where('primary_key',  $route_code)->first();
      $area = Area::where('primary_key',  $route_code)->first();
      $subarea = Subarea::where('primary_key',  $route_code)->first();
      $zone = zone::where('primary_key',  $route_code)->first();

      if ($regions) {
         $data = customers::where('region_id', $regions->id)->get();
      } else if ($subregion) {
         $data = customers::where('region_id', $subregion->Region->id)->get();
      } else if ($area) {
         $data = customers::where('region_id', $area->Subregion->Region->id)->get();
      } else if ($subarea) {
         $data = customers::where('region_id', $subarea->Area->Subregion->Region->id)->get();
      } else if ($zone) {
         $data = customers::where('region_id', $zone->subarea->Area->Subregion->Region->id)->get();
      }
      return response()->json(
         [
            'status' => 200,
            'message' => "Customers filtered by region",
            'data' => $data
         ],
         200
      );
   }
}
