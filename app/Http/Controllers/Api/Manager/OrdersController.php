<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\customers;
use App\Models\inventory\items;
use App\Models\Orders;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
//   public function allOrders(Request $request)
//   {
//
//      return response()->json([
//         'status' => 200,
//         'success' => true,
//         'message' => 'Orders with the Order items, the Sales associate, and the customer',
//         'Data' => Orders::with('OrderItem', 'User', 'Customer')->get(),
//      ]);
//   }

   public function allOrders(Request $request)
   {

      return response()->json([
         'status' => 200,
         'success' => true,
         'message' => 'Orders with the Order items, the Sales associate, and the customer',
         'Data' => Orders::with('OrderItem', 'User', 'Customer')->get(),
      ]);
   }

   public function allOrdersUsingAPIResource()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         // 'Data1' => User::withCount(['Checkings'])->with('Orders.OrderItem')->where('account_type', 'Sales')->get(),
         'Data' => UserResource::collection(
            User::withCount(['Checkings'])->with('Orders.OrderItem')->whereIn('account_type', ['TSR','TD','RSM'])->get()
         ),
      ]);
   }

   public function allOrderForCustomers()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Order information for customers",
         'Data' => CustomerResource::collection(
            customers::withCount(['Checkings'])->with('Orders.OrderItem')->get()
         ),
      ]);
   }
   public function allocateOrders(Request $request){
      $random = Str::random(20);
      info("Manager allocate orders");
      $json = $request->products;
      $data = json_decode($json, true);
      $validator           =  Validator::make($request->all(), [
         "customerID" => "required",
         "user_code" => "required",
         "order_code" => "required",
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
      $customerID=$request->customerID;
      $sales_person=$request->user_code;
      $order_code=$request->order_code;
      Orders::where('order_code', $order_code)->update([
         'user_code'=>$sales_person
      ]);
      return response()->json([
         "success" => true,
         "message" => "Orders allocated successfully",
         "Result"    => "Successful"
      ], 200);

   }
   public function allocationItems(Request $request)
   {
      $data = items::with(["Inventory.User", "Information"])->whereNotIn('inventory_allocated_items.is_approved',['Yes', 'No'])->get();
      $arraydata = array();
      foreach ($data as $value) {
         $arrayFiltered = array();
         $user = $value['inventory'];
//         $inventory = $value['inventory'];
         if ($user !== null) {
            $arrayFiltered["OrderedUser"] = $value["inventory"]["user"] == null ? $this->noValue() : $this->returnFilterUser($value["inventory"]["user"]);
            //$arrayFiltered["ItemDetails"] = $value["information"] == null ? $this->noInformation() : $this->returnFilterInformation($value["information"]);
            $itemDetails = $value["information"] == null ? $this->noInformation() : $this->returnFilterInformation($value["information"]);

            // Add additional fields to ItemDetails
            $itemDetails["allocation_code"] = $value->allocation_code;
            $itemDetails["productID"] = $value->product_code;
            $itemDetails["current_qty"] = $value->current_qty;
            $itemDetails["allocated_qty"] = $value->allocated_qty;
            $arrayFiltered["ItemDetails"] = array_merge($itemDetails, $arrayFiltered["ItemDetails"] ?? []);

            array_push($arraydata, $arrayFiltered);

         }
      }
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Preordered Information",
         'Array' => $arraydata,
      ]);
   }

   public function orderApproval(Request $request)
   {
      info("Manager approve allocated products");
      $json = $request->products;
      $data = json_decode($json, true);
      $validator = Validator::make($request->all(), [
         "productID" => "required",
         "allocation_code" => "required",
      ]);

      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 403,
               "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      $productID = $request->productID;
      $allocation_code = $request->allocation_code;
      if (items::where('allocation_code', $allocation_code)
         ->where('product_code', $productID)
         ->exists()) {
         items::where('allocation_code', $allocation_code)
            ->where('product_code', $productID)
            ->update([
               'approval_time' => Carbon::now(),
               'approved_by' => Auth()->user()->user_code,
               'is_approved' => 'yes'
            ]);
         // return success response
         return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Item approved successfully.'
         ], 200);
      } else {
         // return error response
         return response()->json([
            'status' => 404,
            'success' => false,
            'message' => 'Item not found.'
         ], 404);
      }
   }
   public function orderDisapproval(Request $request)
   {
      info("Manager approve allocated products");
      $json = $request->products;
      $data = json_decode($json, true);
      $validator = Validator::make($request->all(), [
         "productID" => "required",
         "allocation_code" => "required",
      ]);

      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 403,
               "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      $productID = $request->productID;
      $allocation_code = $request->allocation_code;
      if (items::where('allocation_code', $allocation_code)
         ->where('product_code', $productID)
         ->exists()) {
         items::where('allocation_code', $allocation_code)
            ->where('product_code', $productID)
            ->update([
               'approval_time' => Carbon::now(),
               'disapproved_by' => Auth()->user()->user_code,
               'is_approved' => 'no'
            ]);
         // return success response
         return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Item approved successfully.'
         ], 200);
      } else {
         // return error response
         return response()->json([
            'status' => 404,
            'success' => false,
            'message' => 'Item not found.'
         ], 404);
      }
   }

//   public function allocationItems(Request $request)
//   {
//      $data = items::with(["Inventory.User", "Information"])->get();
//      $arraydata = array();
//      foreach ($data as $value) {
//         $arrayFiltered = array();
//         $user = $value['inventory'];
//         if ($user !== null) {
//            $arrayFiltered["OrderedUser"] =
//               $value["inventory"]["user"] == null
//               ? $this->noValue() : $this->returnFilterUser($value["inventory"]["user"]);
//            $arrayFiltered["ItemDetails"] = $value["information"] == null
//               ? $this->noInformation() : $this->returnFilterInformation($value["information"]);
//         }
//         array_push($arraydata, $arrayFiltered);
//      }
//      return response()->json([
//         'status' => 200,
//         'success' => true,
//         "message" => "Preordered Information",
//         'Array' => array_filter($arraydata),
//      ]);
//   }
   public function noValue()
   {
      $arrayData = array();
      $arrayData["id"] = "no value";
      $arrayData["name"] = "no value";
      $arrayData["user_code"] = "no value";
      $arrayData["email"] = "no value";
      return $arrayData;
   }
   public function returnFilterUser($data)
   {
      $arrayData = array();
      if ($data !== null) {
         $arrayData["id"] = $data["id"];
         $arrayData["name"] = $data["name"];
         $arrayData["user_code"] = $data["user_code"];
         $arrayData["email"] = $data["email"];
      }
      return $arrayData;
   }
   public function noInformation()
   {
      $arrayData = array();
      $arrayData["id"] =  "no value";
      $arrayData["item_name"] =  "no value";
      $arrayData["qty_stocked"] = "no value";
      $arrayData["brand"] = "no value";
      $arrayData["total_amount"] = "no value";
      $arrayData["date"] =  "no value";
      return $arrayData;
   }
   public function returnFilterInformation($data)
   {
//      dd($data);
      $arrayData = array();
      if ($data !== null) {
         $arrayData["id"] = $data["id"];
         $arrayData["item_name"] = $data["product_name"];
//         $item = items::where($data["id"])->first();
//         $arrayData["qty_stocked"] = $item->current_stock;
//         $arrayData["allocated_qty"] = $item->allocated_qty;
//         $arrayData["allocation_code"] = $item->allocation_code;
//         $arrayData["productID"] = $item->product_code;
         $arrayData["qty_stocked"] = product_inventory::whereId($data["id"])->pluck("current_stock")->implode('');
         $arrayData["brand"] = $data["brand"];
         $arrayData["total_amount"] = product_price::whereId($data["id"])->pluck("buying_price")->implode('');
         $arrayData["date"] = $data["updated_at"];
      }
      return $arrayData;
   }
   public function transaction(Request $request)
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Order information for customers",
         'Data' => OrderResource::collection(
            Orders::with(['Customer'])->get(),
         ),
         'custom'=> $this->customTransaction($request)->getData(),
      ]);
   }
   public function customTransaction(Request $request)
   {
      return response()->json([
//         'status' => 200,
//         'success' => true,
//         "message" => "Order information for customers",
         'Custom' => OrderResource::collection(
            Orders::with(['Customer'])->period($request->start_date, $request->end_date)->get(),
         ),
      ]);
   }
}

