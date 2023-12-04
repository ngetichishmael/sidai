<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReconciledProducts as ReconciledProducts;
use App\Models\Reconciliation;
use App\Models\warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

          foreach ($request['cart'] as $data) {
             $reconciliation_code=  Str::random(20);
             $reconciled_products = new ReconciledProducts();
             $reconciled_products->productID = $data['productID'];
             $reconciled_products->amount = $data['amount'];
             $reconciled_products->reconciliation_code = $reconciliation_code;
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

//             info("amount is " . $data['amount']);
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
             $cash = $request['cash'];
             $mpesa = $request['mpesa'];
             $cheque = $request['cheque'];
             $bank = $request['bank'];
             Reconciliation::create([
                  'reconciliation_code'=>$reconciliation_code,
                  'cash'=>$cash,
                  'bank'=>$bank,
                  'mpesa'=>$mpesa,
                  'cheque'=>$cheque,
                  'total'=>$data['amount'],
                  'status'=>'waiting_approval',
                  'warehouse_code'=>$warehouse_code ?? $randomWarehouse,
                  'reconciled_to'=>$data['supplierID'],
                  'sales_person'=>$usercode
             ]);
          }

          return response()->json([
             "success" => true,
             "message" => "All products were successfully reconciled",
             "Result" => "Successful"
          ], 200);
       }else{
          foreach ($request['cart']  as $data) {
             $reconciliation_code=  Str::random(20);
             $reconciled_products = new ReconciledProducts();
             $reconciled_products->productID = $data['productID'];
             $reconciled_products->amount = $data['amount'];
             $reconciled_products->reconciliation_code = $reconciliation_code;
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

//             info("amount is " . $data['amount']);
             DB::table('inventory_allocated_items')
                ->where('allocated_qty', '<', 1)
                ->delete();
             DB::table('order_payments')
                ->where('user_id', $id)
                ->update(['isReconcile' => 'true']);
          }
          $cash = $request['cash'];
          $mpesa = $request['mpesa'];
          $cheque = $request['cheque'];
          $bank = $request['bank'];
          Reconciliation::create([
             'reconciliation_code'=>$reconciliation_code,
             'cash'=>$cash,
             'bank'=>$bank,
             'mpesa'=>$mpesa,
             'cheque'=>$cheque,
             'total'=>$data['amount'],
             'status'=>'waiting_approval',
             'warehouse_code'=>$warehouse_code ?? $randomWarehouse,
             'reconciled_to'=>$data['supplierID'],
             'sales_person'=>$usercode
          ]);
          return response()->json([
             "success" => true,
             "message" => "All products were successfully reconciled",
             "Result" => "Successful"
          ], 200);
       }
    }
}
