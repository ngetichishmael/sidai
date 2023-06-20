<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\inventory\allocations;
use App\Models\inventory\items;
use App\Models\products\product_inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class StockLiftController extends Controller
{
   public function index(Request $request)
   {
      $user_code = $request->user()->user_code;
      $business_code = $request->user()->business_code;
      $random = Str::random(20);
      info("Stock Lift");
      $json = $request->products;
      $data = json_decode($json, true);

      $validator           =  Validator::make($request->all(), [
         "image" => "required"
      ]);

      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401,
               "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }

      $image_path = $request->file('image')->store('image', 'public');
      foreach ($data as $value) {
         $stock = items::where('product_code', $value["productID"])
            ->where('created_by', $user_code)
            ->pluck('product_code')
            ->implode('');
         if ($stock == null) {
            $stocked = product_inventory::where('productID', $value["productID"])->first();
            info($stocked);
            $itemchecker = items::create([
               'business_code' => $business_code,
               'allocation_code' => $random,
               'product_code' => $value["productID"],
               'current_qty' => $stocked["current_stock"],
               'allocated_qty' => $value["qty"],
               'image' => $image_path,
               'returned_qty' => 0,
               'created_by' => $user_code,
               'updated_by' => $user_code,
            ]);
//            info("itemchecker");
//            info($itemchecker);
         } else {

            $inventoryallocation = DB::table('inventory_allocated_items')
               ->where('product_code', $value["productID"])
               ->increment('allocated_qty', $value["qty"]);

//            info("inventory_allocated_items");
//            info($inventoryallocation);
         }
         $producting = DB::table('product_inventory')
            ->where('productID', $value["productID"])
            ->decrement('current_stock', $value["qty"]);

//         info("Product inventory");
//         info($producting);
      }
      $checkeddd = allocations::create([
         "business_code" => $business_code,
         "allocation_code" => $random,
         "sales_person" => $user_code,
         "status" => "Waiting acceptance",
         "created_by" => $user_code,
         "updated_by" => $user_code,

      ]);
      return response()->json([
         "success" => true,
         "message" => "All Available Product Information",
         "Result"    => "Successful"
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
        `product_price`.`default_price` as `distributor_price`,
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
