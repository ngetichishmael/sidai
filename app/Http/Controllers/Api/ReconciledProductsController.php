<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReconciledProducts as ReconciledProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use App\Models\warehousing;

class ReconciledProductsController extends Controller
{
   public function index(Request $request)
   {
      $usercode = $request->user()->user_code;
      $id = $request->user()->id;
      $request = $request->collect();

      $randomWarehouse = Warehousing::select('warehouse_code')
                        ->inRandomOrder()
                        ->limit(1)
                        ->get();
      foreach ($request as $data) {
         $reconciled_products = new ReconciledProducts();
         $reconciled_products->productID = $data['productID'];
         $reconciled_products->amount = $data['amount'];
         $reconciled_products->supplierID = $data['supplierID'];
         $reconciled_products->userCode  = $usercode;
         $reconciled_products->warehouse_code  = $randomWarehouse;
         $reconciled_products->save();

         DB::table('inventory_allocated_items')
         ->where('created_by',$usercode)
         ->decrement('allocated_qty',$data['amount'],[
            'updated_at'=>now()
         ]);
         DB::table('inventory_allocated_items')
         ->where('allocated_qty', '<', 1 )
         ->delete();

         DB::table('product_inventory')
         ->where('created_by',$usercode)
         ->increment('current_stock',$data['amount'],[
            'updated_by'=>now(),
            'updated_by'=>$id,
         ]);
         DB::table('order_payments')
         ->where('user_id', $id )
         ->update(['isReconcile'=>'true']);
      }

      return response()->json([
         "success" => true,
         "message" => "All products were successfully reconciled",
         "Result"    => "Successful"
      ]);
   }
}
