<?php

namespace App\Helpers;

use App\Models\inventory\allocations;
use App\Models\inventory\items;
use App\Models\products\product_inventory;
use Illuminate\Support\Facades\DB;


class DistributorStockLiftHelper
{
   public static function updateOrCreateItems(
      $user_code,
      $business_code,
      $value,
      $image_path,
      $random,
      $stocked,
      $distributor
   ) {
      $currentQty = $stocked ? $stocked['current_stock'] : 0;
      $allocatedQty = $value['qty'];
      info("quantity       ". $allocatedQty);
      info(" current qty   ". $allocatedQty);
      items::updateOrCreate(
         [
            'product_code' => $value['productID'],
            'created_by' => $user_code
         ],
         [
            'business_code' => $business_code,
            'allocation_code' => $random,
            'current_qty' => $currentQty,
            'allocated_qty' => DB::raw('allocated_qty + '.$allocatedQty),
            'image' => $image_path,
            'returned_qty' => 0,
            'created_by' => $user_code,
            'updated_by' => $user_code
         ]
      );

//      product_inventory::where('productID', $value['productID'])
//         ->decrement('current_stock', $allocatedQty);

      allocations::updateOrCreate(
         [
            "allocation_code" => $random,
            "sales_person" => $user_code
         ],
         [
            "business_code" => $business_code,
            "status" => "Waiting acceptance",
            "distributor"=>$distributor,
            "created_by" => $user_code,
            "updated_by" => $user_code
         ]
      );
   }
   }
