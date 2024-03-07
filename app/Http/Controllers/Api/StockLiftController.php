<?php

namespace App\Http\Controllers\Api;

use App\Helpers\DistributorStockLiftHelper;
use App\Helpers\StockLiftHelper;
use App\Http\Controllers\Controller;
use App\Models\products\product_inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class StockLiftController extends Controller
{
   public function index(Request $request)
   {
      $user = $request->user();
      $user_code = $user->user_code;
      $business_code = $user->business_code;
      $random = Str::random(20);
//     info("Stock Lift");
      $validator = Validator::make($request->all(), [
         "image" => "required"
      ]);
      $message = '';
      if ($validator->fails()) {
         return response()->json([
            "status" => 401,
            "message" => "validation_error",
            "errors" => $validator->errors()
         ], 403);
      }

      $image_path = $request->file('image')->store('image', 'public');
//      info($image_path);
      if ($request->distributor == 1 || $request->distributor == null) {
         $status = "Waiting acceptance";
         }else
            {
               $status = "Waiting Approval";
            }
      $data = json_decode($request->products, true);
      $productIDs = array_column($data, 'productID');
      $stockedProducts = product_inventory::whereIn('productID', $productIDs)->get()->keyBy('productID');

//      foreach ($data as $value) {
//         $stocked = $stockedProducts->get($value['productID']);
//         (new StockLiftHelper())(
//            $user_code,
//            $business_code,
//            $value,
//            $image_path,
//            $random,
//            $stocked
//         );
//      }
      foreach ($data as $value) {
         $stocked = $stockedProducts->get($value['productID']);
         if ($request->distributor == 1 || $request->distributor ==null) {
            $distributor=1;
            StockLiftHelper::updateOrCreateItems(
               $user_code,
               $business_code,
               $value,
               $image_path,
               $random,
               $stocked,
               $distributor,
               $status,
            );
         }else{
            $distributor=$request->distributor;
            DistributorStockLiftHelper::updateOrCreateItems(
               $user_code,
               $business_code,
               $value,
               $image_path,
               $random,
               $stocked,
               $distributor,
               $status
            );
         }
      }
      return response()->json([
         "success" => true,
         "message" => $message,
         "Result" => "Successful"
      ]);
   }

   public function show(Request $request)
   {
      $query = DB::select('SELECT
        `product_information`.`supplierID` as `SupplierID`,
        `product_information`.`business_code` as `business_code`,
        `product_information`.`sku_code`,
        `product_information`.`brand`,
        `product_information`.`category`,
        `product_information`.`id` AS `productID`,
        `product_information`.`created_at` as `date`,
        `product_information`.`product_name` as `product_name`,
        `product_price`.`selling_price` as `price`,
        `product_price`.`selling_price` as `wholesale_price`,
        `product_price`.`buying_price` as `retail_price`,
        `product_price`.`distributor_price` as `distributor_price`,
        `product_inventory`.`current_stock` AS `current stock`
            FROM
                `product_information`
            INNER JOIN `product_inventory` ON `product_inventory`.`business_code` = `product_information`.`business_code`
            INNER JOIN `product_price` ON `product_price`.`productID` = `product_information`.`id`
            GROUP BY `productID`');

      return response()->json([
         "success" => true,
         "message" => "All Available Product Information filtered by Distributers",
         "data"    => $query
      ]);
   }
   public function receive(Request $request)
   {
      $user_code = $request->user()->user_code;
      $businessCode = $request->user()->business_code;
      $query = DB::select('SELECT
                `product_information`.`id` AS `product ID`,
                `product_information`.`product_name` AS `Product Name`,
                `inventory_allocations`.`status`,
                `inventory_allocations`.`date_allocated`,
                `inventory_allocated_items`.`allocated_qty` AS `Quantity Allocated`,
                `inventory_allocated_items`.`business_code`
            FROM
                `product_information`
            INNER JOIN `inventory_allocations` ON `inventory_allocations`.`business_code` = `product_information`.`business_code`
            INNER JOIN `inventory_allocated_items` ON `inventory_allocations`.`allocation_code` = `inventory_allocated_items`.`allocation_code`
            WHERE
                `product_information`.`business_code` = ? AND `inventory_allocations`.`sales_person` = ?', [$businessCode, $user_code]);

      return response()->json([
         "success" => true,
         "message" => "All Available Product Information",
         "data"    => $query
      ]);
   }
}
