<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockDistributer extends Controller
{
 public function index(){
    $query=DB::query("SELECT
            `name`,
            `email`,
            `phone_number`,
            `telephone`,
            `business_code`
        FROM
            `suppliers`");

        return response()->json([
                "success" => true,
                "message" => "All Suppliers",
                "Data" => $query
            ]);

 }
}
