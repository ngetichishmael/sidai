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
      $jsonData = $request->collect();
      $requestArray = json_decode($jsonData, true);

      if ($requestArray === null && json_last_error() !== JSON_ERROR_NONE) {
         return response()->json([
            "success" => false,
            "message" => "Invalid JSON data",
            "Result" => "Failed"
         ], 400);
      }

      $randomWarehouse = Warehousing::where('warehouse_code', $warehouse_code ?? 1)->first();
      if (isset($requestArray['cart']) && is_array($requestArray['cart'])) {
         info('  ', ['distributor' => $distributor]);
         info("  here  ");
         if ($distributor == 1 || $distributor == null || empty($distributor)) {
            info(' inside distributor ');
            $reconciliation_code = Str::random(20);

            info( "   request array is    ", $requestArray['cart']);
            foreach ($requestArray['cart'] as $data) {
               info(" data     ", $data);
               $reconciliation_code = Str::random(20);
              ReconciledProducts::create([
              'productID' => $data['productID'],
              'amount' => $data['amount'],
              'reconciliation_code' => $reconciliation_code,
              'supplierID' => $data['supplierID'],
              'userCode' => $usercode,
              'warehouse_code' => $warehouse_code ?? $randomWarehouse,
              ]);

//               DB::table('inventory_allocated_items')
//                  ->where('created_by', $usercode)
//                  ->where('product_code', $data['productID'])
//                  ->decrement('allocated_qty', $data['amount'], [
//                     'updated_at' => now(),
//                  ]);
//
//               DB::table('inventory_allocated_items')
//                  ->where('allocated_qty', '<', 1)
//                  ->delete();
//
//               DB::table('product_inventory')
//                  ->where('created_by', $usercode)
//                  ->increment('current_stock', $data['amount'], [
//                     'updated_at' => now(),
//                     'updated_by' => $id,
//                  ]);
//
//               DB::table('order_payments')
//                  ->where('user_id', $id)
//                  ->update(['isReconcile' => 'true']);
            }
            $cash = $requestArray['cash'];
            $mpesa = $requestArray['mpesa'];
            $cheque = $requestArray['cheque'];
            $bank = $requestArray['bank'];
            $totals = $cash + $mpesa + $cheque + $bank;
            Reconciliation::create([
               'reconciliation_code' => $reconciliation_code,
               'cash' => $cash,
               'bank' => $bank,
               'mpesa' => $mpesa,
               'cheque' => $cheque,
               'total' => $totals,
               'supplierID' => $distributor,
               'status' => 'waiting_approval',
               'warehouse_code' => $warehouse_code ?? $randomWarehouse,
               'reconciled_to' => $distributor,
               'sales_person' => $usercode
            ]);
            return response()->json([
               "success" => true,
               "message" => "All products were successfully reconciled",
               "Result" => "Successful"
            ], 200);
         } else {
            $reconciliation_code = Str::random(20);
         foreach ($requestArray['cart'] as $data) {
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

            DB::table('inventory_allocated_items')
               ->where('allocated_qty', '<', 1)
               ->delete();

            DB::table('order_payments')
               ->where('user_id', $id)
               ->update(['isReconcile' => 'true']);
         }
            $cash = $requestArray['cash'];
            $mpesa = $requestArray['mpesa'];
            $cheque = $requestArray['cheque'];
            $bank = $requestArray['bank'];
            $totals = $cash + $mpesa + $cheque + $bank;
            Reconciliation::create([
               'reconciliation_code' => $reconciliation_code,
               'cash' => $cash,
               'bank' => $bank,
               'mpesa' => $mpesa,
               'cheque' => $cheque,
               'total' => $totals,
               'supplierID' => $distributor,
               'status' => 'approved',
               'warehouse_code' => $warehouse_code ?? $randomWarehouse,
               'reconciled_to' => $distributor,
               'sales_person' => $usercode
            ]);
         return response()->json([
            "success" => true,
            "message" => "All products were successfully reconciled",
            "Result" => "Successful"
         ], 200);
      }
   }else {
      return response()->json([
         "success" => false,
         "message" => "'cart' key is missing or not an array",
         "Result" => "Failed"
      ], 400);
   }
   }
   public function index3(Request $request, $warehouse_code, $distributor)
    {
       $usercode = $request->user()->user_code;
       $id = $request->user()->id;
       $jsonData = $request->collect();
       $requestArray = json_decode($jsonData, true);
       if ($requestArray === null && json_last_error() !== JSON_ERROR_NONE) {
          return response()->json([
             "success" => false,
             "message" => "Invalid JSON data",
             "Result" => "Failed"
          ], 400);
       }
       $randomWarehouse = Warehousing::where('warehouse_code', $warehouse_code ?? 1)->first();

       if ($distributor == 1 || $distributor == null) {
          if (isset($requestArray['cart']) && is_array($requestArray['cart'])) {
             foreach ($requestArray['cart'] as $data) {
                $reconciliation_code = Str::random(20);
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
                   'reconciliation_code' => $reconciliation_code,
                   'cash' => $cash,
                   'bank' => $bank,
                   'mpesa' => $mpesa,
                   'cheque' => $cheque,
                   'total' => $data['amount'],
                   'status' => 'waiting_approval',
                   'warehouse_code' => $warehouse_code ?? $randomWarehouse,
                   'reconciled_to' => $data['supplierID'],
                   'sales_person' => $usercode
                ]);
             }

             return response()->json([
                "success" => true,
                "message" => "All products were successfully reconciled",
                "Result" => "Successful"
             ], 200);
          }{
             return response()->json([
                "success" => false,
                "message" => "'cart' key is missing or not an array",
                "Result" => "Failed"
             ], 400);
          }
       } else {
          if (isset($requestArray['cart']) && is_array($requestArray['cart'])) {
             foreach ($requestArray['cart'] as $data) {
                $reconciliation_code = Str::random(20);
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
                'reconciliation_code' => $reconciliation_code,
                'cash' => $cash,
                'bank' => $bank,
                'mpesa' => $mpesa,
                'cheque' => $cheque,
                'total' => $data['amount'],
                'status' => 'waiting_approval',
                'warehouse_code' => $warehouse_code ?? $randomWarehouse,
                'reconciled_to' => $data['supplierID'],
                'sales_person' => $usercode
             ]);
             return response()->json([
                "success" => true,
                "message" => "All products were successfully reconciled",
                "Result" => "Successful"
             ], 200);
          }
          else{
             return response()->json([
                "success" => false,
                "message" => "'cart' key is missing or not an array",
                "Result" => "Failed"
             ], 400);
          }
       }
    }
}
