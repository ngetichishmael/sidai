<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\products;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
   {
      $stockRequisitions = StockRequisition::with('requisitionProducts')->get();
      return response()->json($stockRequisitions);
   }

   public function store(Request $request)
   {
      $requisitionData = $request->validate([
         'requisition_products' => 'required|array',
         'requisition_products.*.product_id' => 'required|integer',
         'requisition_products.*.quantity' => 'required|integer',
      ]);

      $stockRequisition = StockRequisition::create(
         [
            "sales_person"=>$request->user()->user_code,
            "requisition_date"=>Carbon::now(),
            "status"=>"Waiting Approval",

         ]
      );
      foreach ($requisitionData['requisition_products'] as $productData) {
         RequisitionProduct::create([
            'requisition_id' => $stockRequisition->id,
            'product_id' => $productData['product_id'],
            'quantity' => $productData['quantity'],
         ]);
      }
      return response()->json("Stock requisition request successful", 201);
   }

   public function show(StockRequisition $stockRequisition)
   {
      $stockRequisition->load('requisitionProducts')->where('sales_person', Auth::user()->user_code );
      return response()->json([$stockRequisition], 200);
   }

   public function update(Request $request, StockRequisition $stockRequisition)
   {
      $stockRequisition->update($request->all());
      return response()->json($stockRequisition);
   }

   public function destroy(StockRequisition $stockRequisition)
   {
      $stockRequisition->delete();
      return response()->json(null, 204);
   }
   public function cancel(StockRequisition $stockRequisition)
   {
      $stockRequisition->update(['status' => 'Cancelled']);
      return response()->json(null, 204);
   }

}
