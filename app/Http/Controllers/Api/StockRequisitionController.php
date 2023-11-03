<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StockLiftHelper;
use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\inventory\allocations;
use App\Models\inventory\items;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

   public function store(Request $request, $warehouse_code)
   {
      $requisitionData = $request->validate([
         'requisition_products' => 'required|array',
         'requisition_products.*.product_id' => 'required|integer',
         'requisition_products.*.quantity' => 'required|integer',
      ]);

      $stockRequisition = StockRequisition::create(
         [
//                "user_id" => $request->user()->user_code,
            "user_id" => $request->user()->id,
	  "sales_person"=>$request->user()->user_code,
	    "requisition_date" => Carbon::now(),
            "warehouse_code" => $warehouse_code,
            "status" => "Waiting Approval",

         ]
      );
      foreach ($requisitionData['requisition_products'] as $productData) {
         RequisitionProduct::create([
            'requisition_id' => $stockRequisition->id,
            'product_id' => $productData['product_id'],
            'quantity' => $productData['quantity'],
         ]);
      }
      $ativity_rand = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Requisition Request';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile';
      $activityLog->action = 'Stock Requisition Requested by' . Auth::user()->name . ' requisition id  ' . $stockRequisition->id ?? '';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $ativity_rand;
      $activityLog->ip_address = "";
      $activityLog->save();
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
                'date' => $requisition->created_at,
                'requisition_id' => $requisition->id,
                'data' => $products,
            ];
        });

        return response()->json($statusAndData, 200);
    }
    public function approved(Request $request)
    {
        $stockRequisition = StockRequisition::where('id', $request->requisition_id)->with(['RequisitionProducts' => function ($query) {
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
                'message' => 'all accepted requisitions',
                'data' => $products,
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
       info("accepting requisitions");
       info($request->all());
        $selectedProducts = $request->products;
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
            $product = RequisitionProduct::where('requisition_id', $request->requistion_id)->where('product_id', $productId)->first();
            $distributor=1;
            info($product);
            if ($product) {
                $value = [
                    'productID' => $product->product_id,
                    'qty' => $product->quantity,
                ];
                $status='Accepted';

                $stocked = product_inventory::find($product->product_id);
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
