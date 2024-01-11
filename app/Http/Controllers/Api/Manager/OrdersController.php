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
use App\Models\Region;
use App\Models\suppliers\suppliers;
use App\Models\User;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    public function allOrders(Request $request)
    {
        $region_id = $request->user()->route_code;
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Orders with the Order items, the Sales associate, and the customer',
            'Data' => Orders::with('OrderItem', 'User', 'Customer')
                ->whereIn('id', $this->filter($region_id))
                ->get(),
        ]);
    }
    public function pendingOrders(Request $request)
    {
       $sidai=suppliers::find(1);
       $orders=Orders::with('Customer', 'user', 'distributor')
       ->where('order_status','=', 'Pending Delivery')
       ->when($this->user->account_type === "RSM"||$this->user->account_type === "Shop-Attendee",function($query){
          $query->whereIn('customerID', $this->rolefilter());
       })
       ->where(function ($query) use ($sidai) {
          $query->whereNull('supplierID')
             ->orWhere('supplierID', '')
             ->orWhere(function ($subquery) use ($sidai) {
                if ($sidai !== null) {
                   $subquery->where('supplierID', 1);
                }
             });
       })
       ->where('order_type','=','Pre Order')
          ->orderBy('orders.id' ? 'asc' : 'desc');

          return response()->json([
             'status' => 200,
             'success' => true,
             'message' => 'Pending Orders with the Order items, the Sales associate, and the customer',
             'data' => $orders
          ]);

    }
    public function pendingDeriveries(Request $request)
    {
       $sidai=suppliers::find(1);
       $orders =  Delivery::whereNotIn('delivery_status', ['Pending Delivery', 'Partial delivery'])
          ->where(function ($query) use ($sidai) {
             $query->whereHas('Order', function ($subQuery) use ($sidai) {
                $subQuery->whereNull('supplierID')
                   ->orWhere('supplierID', '')
                   ->orWhere('supplierID', 1);
             })->whereHas('Order', function ($subQuery) {
                $subQuery->where('order_type', 'Pre Order');
             });
          })
          ->with('Customer', 'User', 'Order', 'DeliveryItems')
          ->when($this->user->account_type === "RSM"|| $this->user->account_type === "Shop-Attendee",function($query){
             $query->whereIn('customer', $this->rolefilter());
          })->orderBy('delivery.id' ? 'asc' : 'desc');
          return response()->json([
             'status' => 200,
             'success' => true,
             'message' => 'Pending Deliveries with the Order items, the Sales associate, and the customer',
             'data' => $orders

          ]);
    }
    public function pendingDistributorOrders(Request $request)
    {
       $sidai = suppliers::find(1);
       $pendingorders = Orders::with('Customer', 'user', 'distributor')
          ->where(function ($query) use ($sidai) {
             $query->whereNotNull('supplierID')
                ->where('supplierID', '!=', '')
                ->where('supplierID', '!=', 1);
          })
          ->where('order_type','=','Pre Order')
          ->when($this->user->account_type === "RSM"|| strtolower($this->user->account_type) === "shop-attendee",function($query){
             $query->whereIn('customerID', $this->rolefilter());
          })->orderBy('orders.id', 'desc');
          return response()->json([
             'status' => 200,
             'success' => true,
             'message' => 'Distributor Orders List',
             'data' => $pendingorders

          ]);
    }
   public function rolefilter(): array
   {
      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM' || !$user->account_type ==="Shop-Attendee") {
         return $array;
      }
      if ($user->account_type ==="Shop-Attendee"){
         $warehouse=warehouse_assign::where('manager', $user->user_code)->first();
         if (empty($warehouse)) {
            return $array;
         }
         $region=warehousing::where('warehouse_code', $warehouse->warehouse_code)->pluck('region_id');
         $customers = customers::whereIn('region_id', $region)->pluck('id');
         return $customers->toArray();
      }else {
         $regions = Region::where('id', $user_code)->pluck('id');
         if (empty($regions)) {
            return $array;
         }
         $customers = customers::whereIn('region_id', $regions)->pluck('id');
         return $customers->toArray();
      }
      if (empty($customers)) {
         return $array;
      }
      return $customers->toArray();
   }
    public function filter($region_id): array
    {

        $array = [];
        $customers = customers::where('region_id', $region_id)->pluck('id');
        if ($customers->isEmpty()) {
            return $array;
        }
        $orders = Orders::whereIn('customerID', $customers)->pluck('id');
        if ($orders->isEmpty()) {
            return $array;
        }
        return $orders->toArray();
    }
    public function filterOrders($region_id): array
    {

        $array = [];
        $customers = customers::where('region_id', $region_id)->pluck('id');
        if ($customers->isEmpty()) {
            return $array;
        }
        return $customers->toArray();
    }
    public function allOrdersUsingAPIResource(Request $request)
    {
        return response()->json([
            'status' => 200,
            'success' => true,
            'Data' => UserResource::collection(
                User::withCount(['Checkings'])->with('Orders.OrderItem')
                    ->where('route_code', $request->user()->route_code)
                    ->whereIn('account_type', ['TSR', 'TD', 'RSM'])
                    ->get()
            ),
        ]);
    }

    public function allOrderForCustomers(Request $request)
    {
        return response()->json([
            'status' => 200,
            'success' => true,
            "message" => "Order information for customers",
            'Data' => CustomerResource::collection(
                customers::withCount(['Checkings'])
                    ->where('region_id', $request->user()->route_code)
                    ->with('Orders.OrderItem')
                    ->get()
            ),
        ]);
    }

    public function allocateOrders2(Request $request)
    {
        $route_code = $request->user()->route_code;
        $this->validate($request, [
            'account_type' => 'required',
            'order_code' => 'required',
            'products' => 'required',
        ]);
        $supplierID = null;
        $totalSum = 0;
        $quantity = 0;
        $order = Orders::where('order_code', $request->order_code)
            ->first();

        if ($request->account_type === "distributors") {
            $distributor = suppliers::find($request->distributor_id);
            if ($distributor) {
                foreach ($request->products as $product) {
                    $pricing = product_price::where('productID', $product['product_id'])
                        ->first();
                    $orderitems = Order_items::where('order_code', $request->order_code)->where('productID', $product['product_id'])->first();
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
                    } else {
                        return response()->json([
                            "success" => false,
                            "message" => "Something went wrong, Order could not be allocated to distributor",
                            "Result" => "Unsuccessful",
                        ], 409);
                    }
                }
                $supplierID = $distributor->id;
                Orders::where('order_code', $request->order_code)
                    ->update([
                        "supplierID" => $supplierID,
                        "price_total" => $totalSum,
                        "balance" => $totalSum,
                    ]);

                $random = Str::random(20);
                $activityLog = new activity_log();
                $activityLog->activity = 'Allocate an order to a Distributor';
                $activityLog->user_code = auth()->user()->user_code;
                $activityLog->section = 'Mobile';
                $activityLog->action = 'Order allocated to distributor' . $distributor->name . ' ';
                $activityLog->userID = auth()->user()->id;
                $activityLog->activityID = $random;
                $activityLog->ip_address = "";
                $activityLog->save();
                return response()->json([
                    "success" => true,
                    "message" => "Orders allocated to the distributor successfully",
                    "Result" => "Successful",
                ], 200);
            } else {
                Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
                return response()->json([
                    "success" => false,
                    "message" => "Something went wrong, Order could not be allocated to distributor",
                    "Result" => "Unsuccessful",
                ], 200);
            }
        }

        $delivery = Delivery::updateOrCreate(
            [
                "business_code" => Str::random(20),
                "customer" => $order->customerID,
                "order_code" => $request->order_code,
            ],
            [
                "delivery_code" => Str::random(20),
                "allocated" => $request->user_code,
                "delivery_note" => $request->note,
                "delivery_status" => "Waiting acceptance",
                "created_by" => Auth::user()->user_code,
            ]
        );
        foreach ($request->products as $product) {

            $pricing = product_price::where('productID', $product['product_id'])->first();
            $details = product_information::whereId($product['product_id'])->first();
//         $totalSum += $pricing->selling_price * $product['allocated_quantity'];
            $orderitems = Order_items::where('order_code', $request->order_code)->where('productID', $product['product_id'])->first();
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
                        "created_by" => Auth::user()->user_code,
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

                $quantity += 1;
            } else {
                Delivery::destroy($delivery->delivery_code);
                return response()->json([
                    "success" => false,
                    "status" => 409,
                    "message" => "Something went wrong, Order could not be allocated to user",
                    "Result" => "Unsuccessful",
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
        $activityLog->section = 'Mobile';
        $activityLog->action = 'Order allocated to user ' . $request->user_code . ' Role ' . $request->account_type . '';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();

        return response()->json([
            "success" => true,
            "message" => "Orders allocated successfully",
            "Result" => "Successful",
        ], 200);

    }
    public function allocateOrders(Request $request)
    {
        $random = Str::random(20);
//      info("Manager allocate orders");
        $json = $request->products;
        $data = json_decode($json, true);
        $validator = Validator::make($request->all(), [
            "customerID" => "required",
            "user_code" => "required",
            "order_code" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => 401,
                    "message" => "validation_error",
                    "errors" => $validator->errors(),
                ],
                403
            );
        }
        $customerID = $request->customerID;
        $sales_person = $request->user_code;
        $order_code = $request->order_code;
        Orders::where('order_code', $order_code)->update([
            'user_code' => $sales_person,
        ]);
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Manager order allocation';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Mobile';
        $activityLog->action = 'Manager ' . auth()->user()->name . ' allocated order ' . $order_code . ' to ' . $sales_person . ' of customer ' . $customerID;
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();
        return response()->json([
            "success" => true,
            "message" => "Orders allocated to the user successfully",
            "Result" => "Successful",
        ], 200);

    }
    public function allocationItems(Request $request)
    {
        $data = items::with(["Inventory.User", "Information"])->whereNotIn('inventory_allocated_items.is_approved', ['Yes', 'No'])->get();
        $arraydata = array();
        foreach ($data as $value) {
            $arrayFiltered = array();
            $user = $value['inventory'];
            if ($user !== null) {
                $arrayFiltered["OrderedUser"] = $value["inventory"]["user"] == null ? $this->noValue() : $this->returnFilterUser($value["inventory"]["user"]);
                $itemDetails = $value["information"] == null ? $this->noInformation() : $this->returnFilterInformation($value["information"]);
                $itemDetails["allocation_code"] = $value->allocation_code;
                $itemDetails["productID"] = $value->product_code;
                $itemDetails["current_qty"] = $value->current_qty;
                $itemDetails["allocated_qty"] = $value->allocated_qty;
                $arrayFiltered["ItemDetails"] = array_merge($itemDetails, $arrayFiltered["ItemDetails"] ?? []);

                array_push($arraydata, $arrayFiltered);

            }
        }
       $action="Viewed allocated items";
       $activity="Viewd Allocated items on mobile managers app";
       $this->activitylogs($action, $activity);
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
                    "errors" => $validator->errors(),
                ],
                403
            );
        }
        $productID = $request->productID;
        $allocation_code = $request->allocation_code;

        if (items::where('allocation_code', $allocation_code)
            ->where('product_code', $productID)
            ->exists()) {
           $q=items::where('allocation_code', $allocation_code)
              ->where('product_code', $productID)->first();
           $check=product_inventory::where('productID', $productID)->first();
           if ($check->current_stock < $q ){
              return response()->json([
                 'status' => 404,
                 'success' => false,
                 'message' => 'Item quantities is not enough for allocations',
              ], 404);
           }
            items::where('allocation_code', $allocation_code)
                ->where('product_code', $productID)
                ->update([
                    'approval_time' => Carbon::now(),
                    'approved_by' => Auth()->user()->user_code,
                    'is_approved' => 'yes',
                ]);
           $action="Item approval";
           $activity="Approved items for allocation code ".$allocation_code;
           $this->activitylogs($action, $activity);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Item approved successfully.',
            ], 200);
        } else {
            // return error response
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Item not found.',
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
                    "errors" => $validator->errors(),
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
                    'is_approved' => 'no',
                ]);
           $action="Item disapproval";
           $activity="Disapproved items for allocation code ".$allocation_code;
           $this->activitylogs($action, $activity);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Item approved successfully.',
            ], 200);
        } else {
            // return error response
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
    }
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
        $arrayData["id"] = "no value";
        $arrayData["item_name"] = "no value";
        $arrayData["qty_stocked"] = "no value";
        $arrayData["brand"] = "no value";
        $arrayData["total_amount"] = "no value";
        $arrayData["date"] = "no value";
        return $arrayData;
    }
    public function returnFilterInformation($data)
    {
        $arrayData = array();
        if ($data !== null) {
            $arrayData["id"] = $data["id"];
            $arrayData["item_name"] = $data["product_name"];
            $arrayData["qty_stocked"] = product_inventory::whereId($data["id"])->pluck("current_stock")->implode('');
            $arrayData["brand"] = $data["brand"];
            $arrayData["total_amount"] = product_price::whereId($data["id"])->pluck("buying_price")->implode('');
            $arrayData["date"] = $data["updated_at"];
        }
        return $arrayData;
    }
    public function transaction(Request $request)
    {
        if ($request->user()->account_type === 'RSM') {
            return response()->json([
                'status' => 200,
                'success' => true,
                "message" => "Order information for customers",
                'Data' => OrderResource::collection(
                    Orders::with(['Customer'])->get(),
                ),
                'custom' => $this->customTransaction($request)->getData(),
            ]);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            "message" => "Order information for customers",
            'Data' => OrderResource::collection(
                Orders::with(['Customer'])->get(),
            ),
            'custom' => $this->customTransaction($request)->getData(),
        ]);
    }
    public function customTransaction(Request $request)
    {
        return response()->json([
            'Custom' => OrderResource::collection(
                Orders::with(['Customer'])->period($request->start_date, $request->end_date)->get(),
            ),
        ]);
    }
   public function activitylogs($activity,$action): void
   {
      $rdm = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = $activity;
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile';
      $activityLog->action =  $action;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $rdm;
      $activityLog->ip_address = session('login_ip');
      $activityLog->save();
   }
}
