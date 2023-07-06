<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StockLiftHelper;
use App\Http\Controllers\Controller;
use App\Imports\products;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
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
            "user_id"=>$request->user()->id,
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
   public function show(Request $request)
   {
      $stockRequisition = StockRequisition::with('RequisitionProducts')
         ->where('sales_person', $request->user()->user_code)
         ->get();

      $statusAndData = $stockRequisition->map(function ($requisition) {
         $products = $requisition->RequisitionProducts->map(function ($product) {
            $productInformation = product_information::where('id', $product->product_id)->first();
            $product->product_name = $productInformation->product_name;
            return $product;
         });

         return [
            'status' => $requisition->status,
            'date'=>$requisition->created_at,
            'data' => $products
         ];
      });

      return response()->json($statusAndData, 200);
   }
   public function approved(Request $request)
   {
      $stockRequisition = StockRequisition::with(['RequisitionProducts' => function ($query) {
         $query->where('approval', 1);
      }])
         ->where('sales_person', $request->user()->user_code)
         ->get();
      $statusAndData = $stockRequisition->map(function ($requisition) {
         $products = $requisition->RequisitionProducts->map(function ($product) {
            $productInformation = product_information::where('id', $product->product_id)->first();
            $product->product_name = $productInformation->product_name;
            return $product;
         });

         return [
            'status' => 200,
            'message'=>'all accepted requisitions',
            'data' => $products
         ];
      });

      return response()->json($statusAndData, 200);
   }

   public function update(Request $request, StockRequisition $stockRequisition)
   {
      $stockRequisition->update($request->all());
      return response()->json($stockRequisition);
   }
   public function accept(Request $request)
   {
      $selectedProducts = $request->accepted_products;
      $user = $request->user();
      $user_code = $user->user_code;
      $business_code = $user->business_code;
      $random = Str::random(20);
      $image_path = 'image/92Ct1R2936EUcEZ1hxLTFTUldcSetMph6OGsWu50.png';
      if (empty($selectedProducts)) {
         return response()->json([
            'status' => 409,
            'success' => false,
            "message" => "Not products selected for acceptance",
         ]);
      }
      foreach ($selectedProducts as $productId) {
         $product = RequisitionProduct::where('requisition_id', $request->requisition_id)->where('product_id',$productId)->get();

         if ($product) {
            $value = [
               'productID' => $product->product_id,
               'qty' => $product->quantity,
            ];

            $stocked = product_inventory::find($product->product_id);
            (new StockLiftHelper())(
               $user_code,
               $business_code,
               $value,
               $image_path,
               $random,
               $stocked
            );
         }
      }
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "You have accepted the products, they will now be under your allocation",
      ]);
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
