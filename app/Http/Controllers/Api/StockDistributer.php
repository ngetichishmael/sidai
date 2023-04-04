<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\suppliers\suppliers;

class StockDistributer extends Controller
{
   public function index()
   {
      return response()->json([
         "success" => true,
         "message" => "All Suppliers",
         "Data" => suppliers::all(),
      ]);
   }
}
