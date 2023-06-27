<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\activity_log;
use App\Models\customers;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\inventory\items;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\suppliers\suppliers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
   public function allocateOrders2(Request $request)
   {
      $this->validate($request, [
         'account_type' => 'required',
         'order_code' => 'required',
         'products' => 'required',
//         'warehouse' => 'required',
      ]);
      $supplierID = null;
      $totalSum=0;
      $quantity=0;
      $order = Orders::where('order_code', $request->order_code)->first();

      if ($request->account_type === "distributors") {
         $distributor = suppliers::find($request->distributor_id);
         if ($distributor) {
            foreach ($request->products as $product){
               $pricing = product_price::where('productID', $product['product_id'])->first();
               $orderitems=Order_items::where('order_code', $request->order_code)->where('productID', $product['product_id'])->first();
               if ($orderitems) {
                  $subtotal = $pricing->selling_price * $product['allocated_quantity'];
                  $totalSum += $subtotal;
                  Order_items::where('productID', $product['product_id'])
                     ->where('order_code', $request->order_code)
                     ->update([
                        "requested_quantity" => intval($orderitems->quantity),
                        "allocated_quantity" => $product['allocated_quantity'],
                        "allocated_subtotal" => $subtotal,
                        "allocated_totalamount" => $totalSum,
                     ]);
               }else{
                     return response()->json([
                        "success" => false,
                        "message" => "Something went wrong, Order could not be allocated to distributor",
                        "Result"    => "Unsuccessful"
                     ], 409);
               }
            }
            $supplierID = $distributor->id;
            Orders::where('order_code', $request->order_code)
               ->update([
                  "supplierID" => $supplierID,
                  "price_total" =>$totalSum,
                  "balance" =>$totalSum,
               ]);

            $random = Str::random(20);
            $activityLog = new activity_log();
            $activityLog->activity = 'Allocate an order to a Distributor';
            $activityLog->user_code = auth()->user()->user_code;
            $activityLog->section = 'Order Allocation';
            $activityLog->action = 'Order allocated to distributor' . $distributor->name . ' ';
            $activityLog->userID = auth()->user()->id;
            $activityLog->activityID = $random;
            $activityLog->ip_address = "";
            $activityLog->save();
            return response()->json([
               "success" => true,
               "message" => "Orders allocated to the distributor successfully",
               "Result"    => "Successful"
            ], 200);
         }else{
            Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
            return response()->json([
               "success" => false,
               "message" => "Something went wrong, Order could not be allocated to distributor",
               "Result"    => "Unsuccessful"
            ], 200);
         }
      }

      $delivery = Delivery::updateOrCreate(
         [
            "business_code" => Str::random(20),
            "customer" => $order->customerID,
            "order_code" => $request->order_code
         ],
         [
            "delivery_code" => Str::random(20),
            "allocated" => $request->user_code,
            "delivery_note" => $request->note,
            "delivery_status" => "Waiting acceptance",
            "created_by" => Auth::user()->user_code
         ]
      );
      foreach ($request->products as $product)  {

         $pricing = product_price::where('productID', $product['product_id'])->first();
         $details=product_information::whereId($product['product_id'])->first();
//         $totalSum += $pricing->selling_price * $product['allocated_quantity'];
         $orderitems=Order_items::where('order_code', $request->order_code)->where('productID', $product['product_id'])->first();
         $subtotal = ($pricing->selling_price * $product['allocated_quantity']);
         $totalSum += $subtotal;
         //dump("order ".$order, "orderitem ".intval($orderitems->quantity),"pricing ".$pricing->selling_price, "details ".$details->product_name);
         if ($orderitems) {

         Delivery_items::updateOrCreate(
            [
               "business_code" => Auth::user()->business_code,
               "delivery_code" => $delivery->delivery_code,
               "productID" => $product['product_id'],
            ],
            [
               "selling_price" => $pricing->selling_price,
               "sub_total" => $subtotal,
               "total_amount" => $totalSum,
//               "total_amount" => $request->price[$i],
               "product_name" => $details->product_name,
               "allocated_quantity" => $product['allocated_quantity'],
               "delivery_item_code" => Str::random(20),
               "requested_quantity" => intval($orderitems->quantity),
               "created_by" => Auth::user()->user_code
            ]
         );
         Order_items::where('productID', $product['product_id'])
            ->where('order_code', $request->order_code)
            ->update([
               "requested_quantity" => intval($orderitems->quantity),
               "allocated_quantity" => $product['allocated_quantity'],
               "allocated_subtotal" => $subtotal,
               "allocated_totalamount" => $totalSum,
            ]);

//         if ($request->product[$i] < $request->allocate[$i]) {
//            Orders::where('order_code', $request->order_code)
//               ->update([
//                  "order_status" => "Partial delivery"
//               ]);
//         }else{
//         }
         $quantity +=1;
         }else{
            Delivery::destroy($delivery->delivery_code);
            return response()->json([
               "success" => false,
               "status"=> 409,
               "message" => "Something went wrong, Order could not be allocated to user",
               "Result"    => "Unsuccessful"
            ], 409);
         }
      }


      if ($order) {
         $order->update([
            "order_status" => "Waiting acceptance",
            "price_total" => $totalSum,
            "balance" => $totalSum,
            "initial_total_price" => $order->price_total,
            "updated_qty" => $quantity,
         ]);
      }
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Allocate an order to a User';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Order Allocation';
      $activityLog->action = 'Order allocated to user '. $request->user_code. ' Role '.$request->account_type.'';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return response()->json([
         "success" => true,
         "message" => "Orders allocated successfully",
         "Result"    => "Successful"
      ], 200);

   }
   public function allocateOrders(Request $request){
      $random = Str::random(20);
//      info("Manager allocate orders");
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
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Manager order allocation';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Allocate orders';
      $activityLog->action = 'Manager '.auth()->user()->name.' allocated order '.$order_code.' to '.$sales_person . ' of customer '.$customerID;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      return response()->json([
         "success" => true,
         "message" => "Orders allocated to the user successfully",
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
      if ($request->user()->account_type ==='RSM'){
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

