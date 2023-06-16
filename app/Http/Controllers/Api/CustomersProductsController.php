<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\products\product_information;
use App\Models\products\product_price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomersProductsController extends Controller
{
   public function getAllProducts(Request $request)
   {
      $userRegionId = $request->user()->region_id;
      if ($request->user()->region_id == null){
         $productInfo = product_information::with(['ProductPrice' => function ($query) use ($userRegionId) {
            $query->whereHas('Warehouse', function ($query) use ($userRegionId) {
               $query->where('region_id', $userRegionId);
            });
         }])->get();
//         $productinfo = product_information::with('ProductPrice')->get();
         return response()->json([
            "success" => true,
            "message" => "Product information",
            "products" => $productInfo
         ]);
      }else
         $productInfo = product_information::with(['ProductPrice' => function ($query) use ($userRegionId) {
            $query->whereHas('Warehouse', function ($query) use ($userRegionId) {
               $query->where('region_id', $userRegionId);
            });
         }])->get();      return response()->json([
         "success" => true,
         "message" => "Product information",
         "products" => $productInfo
      ]);
   }
//   public function getAllProducts()
//   {
//      $productinfo = product_information::with('ProductPrice')->all();
//      return response()->json([
//         "success" => true,
//         "message" => "Product information",
//         "products" => $productinfo
//      ]);
//   }
   public function getAllProductsOffers()
   {
      $productOffers = product_price::whereNotNull('offer_price')
         ->with('ProductInfo')
         ->where('time_valid', '>=', Carbon::now())
         ->with('ProductInfo.ProductPrice')
         ->get(['id', 'selling_price', 'time_valid', 'offer_price', 'productID']);

      $formattedProductOffers = [];

      foreach ($productOffers as $offer) {
         $formattedProductOffers[] = [
            'Id' => $offer->id,
            'selling_price' => $offer->selling_price,
            'time_valid' => $offer->time_valid,
            'offer_price' => $offer->offer_price,
            'product' => $offer->ProductInfo
         ];
      }

      return response()->json([
         "status" => true,
         "message" => "Filtered product offers information",
         "result" => $formattedProductOffers
      ], 200);
   }


   public function sendDefaultImage(Request $request)
   {
      $validator           =  Validator::make($request->all(), [
         "image" =>  "required|image|mimes:png,jpg,svg,ico"
      ]);

      $image_path = $request->file('image')->store('image', 'public');
      product_information::updated([
         'image' => $image_path
      ]);
      return response()->json([
         "success" => true,
         "message" => "Total Counts for Orders and Checkings",
         "checkingCount" => $validator
      ]);
   }
}
