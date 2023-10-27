<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReconciledProducts as ReconciledProducts;
use App\Models\warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconciledProductsController extends Controller
{
    public function index2(Request $request, $warehouse_code, $distributor)
    {
       $usercode = $request->user()->user_code;
       $id = $request->user()->id;
       $request = $request->collect();

//       $randomWarehouse = Warehousing::select('warehouse_code')
//          ->inRandomOrder()
//          ->limit(1)
//          ->pluck('warehouse_code');

       $randomWarehouse = Warehousing::where('warehouse_code', $warehouse_code ?? 1)->first();
       if ($distributor == 1 || $distributor == null) {

          foreach ($request as $data) {
             $reconciled_products = new ReconciledProducts();
             $reconciled_products->productID = $data['productID'];
             $reconciled_products->amount = $data['amount'];
             $reconciled_products->supplierID = $data['supplierID'];
             $reconciled_products->userCode = $usercode;
             $reconciled_products->warehouse_code = $warehouse_code ?? $randomWarehouse;
             $reconciled_products->save();

             $is = DB::table('inventory_allocated_items')
                ->where('created_by', $usercode)
                ->where('product_code', $data['productID'])
                ->decrement('allocated_qty', $data['amount'], [
                   'updated_at' => now(),
                ]);

             info("amount is " . $data['amount']);
             DB::table('inventory_allocated_items')
                ->where('allocated_qty', '<', 1)
                ->delete();

             DB::table('product_inventory')
                ->where('created_by', $usercode)
                ->increment('current_stock', $data['amount'], [
                   'updated_at' => now(),
                   'updated_by' => $id,
                ]);
             DB::table('order_payments')
                ->where('user_id', $id)
                ->update(['isReconcile' => 'true']);
          }

          return response()->json([
             "success" => true,
             "message" => "All products were successfully reconciled",
             "Result" => "Successful"
          ]);
       }else{
          foreach ($request as $data) {
             $reconciled_products = new ReconciledProducts();
             $reconciled_products->productID = $data['productID'];
             $reconciled_products->amount = $data['amount'];
             $reconciled_products->supplierID = $data['supplierID'];
             $reconciled_products->userCode = $usercode;
             $reconciled_products->warehouse_code = $warehouse_code ?? $randomWarehouse;
             $reconciled_products->save();

             $is = DB::table('inventory_allocated_items')
                ->where('created_by', $usercode)
                ->where('product_code', $data['productID'])
                ->decrement('allocated_qty', $data['amount'], [
                   'updated_at' => now(),
                ]);

             info("amount is " . $data['amount']);
             DB::table('inventory_allocated_items')
                ->where('allocated_qty', '<', 1)
                ->delete();
             DB::table('order_payments')
                ->where('user_id', $id)
                ->update(['isReconcile' => 'true']);
          }
          return response()->json([
             "success" => true,
             "message" => "All products were successfully reconciled",
             "Result" => "Successful"
          ]);
       }
    }

}
