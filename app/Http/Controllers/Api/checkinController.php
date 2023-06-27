<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Customers\Region;
use App\Models\activity_log;
use App\Models\Delivery;
use App\Models\suppliers\suppliers;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use App\Models\customer\customers;
use App\Models\customer\checkin;
use App\Models\inventory\allocations;
use App\Models\products\product_information;
use App\Models\Order_edit_reason;
use App\Models\Orders;
use App\Models\Order_items;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Models\Route_customer;
use App\Models\Routes;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * @group Checkin Api's
 *
 * APIs to manage customer checkin
 * */

class checkinController extends Controller
{
   /**
    * Create Customer Checkin Session
    *
    * @bodyParam customerID string required as Customer ID
    * @bodyParam latitude string required as current latitude
    * @bodyParam longitude string required as current longitude
    * @bodyParam user_code string required user code
    **/
   public function create_checkin_session(Request $request)
   {
      $id = $request->user()->id ?? 2;
      $customer = customers::where('id', $request->customerID)->first();
      $checkingCount = checkin::where('customer_id', $request->customerID)
         ->where('user_code', $request->user_code)
         ->count();
      $orderCount = Orders::where('customerID', $request->customerID)
         ->where('user_code', $request->user_code)
         ->count();

      // $user = User::where('user_code', $request->user_code)->first();

      $lat1 = $customer->latitude;
      $lon1 = $customer->longitude;
      $lat2 = $request->latitude;
      $lon2 = $request->longitude;
      $unit = "K";

      $checking_code = $request->checkin_code == null ? Helper::generateRandomString(20) : $request->checking_code;
      $startTime = $request->startTime == null ? date('H:i:s') : $request->startTime;
      $distance = round(Helper::distance($lat1, $lon1, $lat2, $lon2, $unit), 2);

      //if($distance < 0.05){

      //create a check in session
      $checkin = new checkin;
      $checkin->code           = $checking_code;
      $checkin->customer_id    = $customer->id;
      $checkin->account_number = $request->customerID;
      $checkin->checkin_type   =  $this->checkVisit($id, $request->customerID);
      $checkin->user_code      = $request->user_code;
      $checkin->ip             = Helper::get_client_ip();
      $checkin->start_time     = $startTime;
      $checkin->business_code  = $customer->business_code;
      $checkin->save();

      //record activity
      // $activities = '<b>'.$user->name.'</b> Has <b>Checked-in</b> to <i> '.$customer->customer_name.'</i> @ '.date('H:i:s');
      // $section = 'Customer';
      // $action = 'Checkin';
      // $business_code = $customer->business_code;
      // $activityID = $checkin->code;

      // Helper::activity($activities,$section,$action,$activityID,$business_code);
      DB::table('visits_targets')
         ->where('user_code', $request->user_code)
         ->increment('AchievedVisitsTarget');
      return response()->json([
         "success" => true,
         "message" => "Checking Session Created Successfully",
         "checking Code" => $checkin->code,
         "checkingCount" => $checkingCount,
         "orderCount" => $orderCount,
         "data" => $checkin->checkin_type
      ]);
   }
   public function checkVisit($user_id, $customer_id)
   {

      $today = Carbon::today()->format('Y-m-d');
      $visit = null;
      $checkerSelf = Routes::with([
         'RouteSales' => function ($query) use ($user_id) {
            $query->where('userID', $user_id);
         }
      ])
         ->where('Type', 'Individual')
         ->where('start_date', '<', $today)
         ->where('end_date', '>', $today)
         ->pluck('route_code');
      $checkerAdmin = Routes::with([
         'RouteSales' => function ($query) use ($user_id) {
            $query->where('userID', $user_id);
         }
      ])
         ->where('Type', 'Assigned')
         ->where('start_date', '<', $today)
         ->where('end_date', '>', $today)
         ->pluck('route_code');
      if (count($checkerSelf) > 0) {
         $route_customer = Route_customer::where('customerID', $customer_id)->whereIn('routeID', $checkerSelf)->get();
         if (count($route_customer) > 0) {
            $visit = 'self';
         }
      }
      if (count($checkerAdmin) > 0) {
         $route_customer = Route_customer::where('customerID', $customer_id)->whereIn('routeID', $checkerAdmin)->get();
         if (count($route_customer) > 0) {
            $visit = 'admin';
         }
      }
      return $visit;
   }
   /**
    * Customer Checkin
    *
    * @param $checkinCode this is the checkin code created when creating checking session
    **/
   public function checkin(Request $request, $checkinCode)
   {
      $checkin = checkin::where('code', $checkinCode)->first();
      $customer = customers::where('id', $checkin->customer_id)->first();


      return response()->json([
         "success" => true,
         "message" => "Checking details",
         "checkin" => $checkin,
         "customer" => $customer
      ]);
   }


   /**
    * stock
    *
    * @param $checkinCode
    **/
   public function stock($checkinCode)
   {
      $cartTotal = Cart::where('checkin_code', $checkinCode)->count();

      return response()->json([
         "success" => true,
         "message" => "You have checked out",
         "stock" => $cartTotal
      ]);
   }

   /**
    * Customer checkout
    *
    * @param $checkinCode
    **/
   public function checkout($checkinCode)
   {
      //check if cart has items
      $cart = Cart::where('checkin_code', $checkinCode)->orderby('id', 'desc')->count();
      if ($cart != 0) {
         return response()->json([
            "success" => true,
            "warning" => "You still have order in your cart",
         ]);
      }

      $checkin = checkin::where('code', $checkinCode)->first();
      $checkin->stop_time = date('H:i:s');
      $checkin->save();

      $customer = customers::where('account', $checkin->account_number)->first();

      //record activity
      //$activities = '<b>'.Auth::user()->name.'</b> Has <b>Checked-out</b> to <i> '.$customer->customer_name.'</i> @ '.date('H:i:s');
      //$section       = 'Customer';
      //$action        = 'Checkin';
      //$business_code = Auth::user()->business_code;
      //$activityID    = $checkin->code;

      //Helper::activity($section,$action,$activityID,$business_code);

      return response()->json([
         "success" => true,
         "message" => "You have checked out",
      ]);
   }


   /**
    * Add to cart
    *
    * @param $checkinCode
    * @bodyParam productID string required as product ID
    * @bodyParam qty string required as quantity
    **/
   public function add_to_cart(Request $request, $checkinCode)
   {
      // $this->validate($request,[
      //    'productID' => 'required',
      //    'qty' => 'required',
      // ]);

      $checkin = checkin::where('code', $checkinCode)->first();
      $user_code = $request->user()->user_code;
      $user = $request->user()->id;

      $request = $request->collect();
      foreach ($request as $value) {
         $product = product_information::join('product_price', 'product_price.productID', '=', 'product_information.id')
            ->where('product_information.id', $value["productID"])
            ->where('product_information.business_code', $checkin->business_code)
            ->first();

         $checkInCart = Cart::where('checkin_code', $checkinCode)->where('productID', $value["productID"])->count();

         if ($checkInCart > 0) {
            $cart = Cart::where('checkin_code', $checkinCode)->where('productID', $value["productID"])->first();
            $cart->qty = $value["qty"];
            $cart->price = $product->selling_price;
            $cart->amount = $value["qty"] * $product->selling_price;
            $cart->total_amount = $value["qty"] * $product->selling_price;
            $cart->userID = $user_code;
            $cart->save();
         } else {
            $cart = new Cart;
            $cart->productID = $value["productID"];
            $cart->product_name = $product->product_name;
            $cart->qty = $value["qty"];
            $cart->price = $product->selling_price;
            $cart->amount = $value["qty"] * $product->selling_price;
            $cart->userID = $user_code;
            $cart->customer_account = $checkin->account_number;
            $cart->total_amount = $value["qty"] * $product->selling_price;
            $cart->checkin_code = $checkinCode;
            $cart->save();
         }
      }
      $customer = customers::where('account', $checkin->account_number)->first();
      $random = Str::random(20);
            $activityLog = new activity_log();
            $activityLog->activity = 'Checkin order';
            $activityLog->user_code = auth()->user()->user_code;
            $activityLog->section = 'Product added to order';
            $activityLog->action = 'Product added to order by' . $request->user()->name .  ' for customer ' .$customer->customer_name ?? $checkin->account_number;
            $activityLog->userID = auth()->user()->id;
            $activityLog->activityID = $random;
            $activityLog->ip_address = "";
            $activityLog->save();

      return response()->json([
         "success" => true,
         "message" => "Product added to order",
         "data"    => $checkin
      ]);


      // Session::flash('success','Product added to order');

   }


   /**
    * Cart
    *
    * @param $checkinCode
    * @bodyParam productID string required as product ID
    * @bodyParam qty string required as quantity
    **/
   public function cart($checkinCode)
   {
      $products = Cart::where('checkin_code', $checkinCode)->orderby('id', 'desc')->get();

      return response()->json([
         "success" => true,
         "message" => "All Products add to cart",
         "products" => $products,
      ]);
   }
   public function distributorschangeStatus( Request  $request )
   {
      $orderStatus = $request->order_status;
      $code = $request->order_code;

      Orders::where('order_code', $code)->update(['order_status' => $orderStatus]);
      Delivery::where('order_code', $code)->update(['delivery_status' => $orderStatus]);

      return response()->json([
         "success" => true,
         "message" => "Distributor Order Status Updated Successfully",
      ], 200);
   }

   /**
    * Save order
    *
    * @param $checkinCode string as checkin Code
    * @bodyParam order_type string required as order type [Van sale or Pre order]
    * @bodyParam reasons_partial_delivery string as reasons_partial_delivery
    **/
   public function save_order(Request $request, $checkinCode)
   {

      $this->validate($request, [
         'order_type' => 'required',
      ]);

      //checkin details
      $checkin = checkin::where('code', $checkinCode)->first();


      //get cart items
      $cart = Cart::where('checkin_code', $checkinCode)->get();
      $region = Region::where('id', $request->user()->region_id)->first();
      $regionCode = strtoupper(substr($region->name, 0, 3));
      $orderCount = Orders::where('region_id', $region->id)->count() + 1;
      $orderNumber = str_pad($orderCount, 5, '0', STR_PAD_LEFT);
      $orderCode = $regionCode . '-' . $orderNumber;
//      $orderCode = Helper::generateRandomString(8);
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      //order
      $order = new Orders;
      $order->order_code =  $orderCode;
      $order->user_code = Auth::user()->user_code;
      $order->business_code = Auth::user()->business_code;
      $order->customerID = $checkin->customer_id;
      $order->checkin_code = $checkinCode;
      $order->price_total = $cart->sum('amount');
      $order->order_status = 'Pending Delivery';
      $order->payment_status = 'Pending Payment';
      $order->qty = $cart->sum('qty');
      $order->order_type = $request->order_type;
      $order->supplierID = $request->distributor ?? $sidai ?? 1;
      $order->balance = $cart->sum('amount');
      $order->delivery_date = $request->delivery_date;
      $order->reasons_partial_delivery = $request->reasons_partial_delivery;
      $order->save();

      //create order items
      foreach ($cart as $item) {
         $cartItem =  Cart::where('checkin_code', $checkinCode)->where('id', $item->id)->first();

         $orderItems = new Order_items;
         $orderItems->order_code =  $orderCode;
         $orderItems->productID =  $cartItem->productID;
         $orderItems->product_name = $cartItem->product_name;
         $orderItems->quantity =  $cartItem->qty;
         $orderItems->sub_total =  $cartItem->amount;
         $orderItems->total_amount =  $cartItem->total_amount;
         $orderItems->selling_price =  $cartItem->price;
         $orderItems->discount =  $cartItem->discount;
         $orderItems->taxrate =  $cartItem->tax_rate;
         $orderItems->taxvalue =  $cartItem->tax_value;
         $orderItems->save();

         //delete item
         $cartItem->delete();
      }
         if ($request->distributor != $sidai->id && $request->distributor !=null ){
            $usersToNotify = Suppliers::findOrFail($request->distributor);
               Notification::send($usersToNotify, new NewOrderNotification($order->id, ));
         }

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Order created successfully';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Order creation';
      $activityLog->action = 'Order created by' . $request->user()->name . ' order code  '.$orderCode;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = "";
      $activityLog->save();

      return response()->json([
         "success" => true,
         "message" => "Order created successfully",
         "orderCode" => $orderCode,
         "checkinCode" => $checkinCode,
      ]);
   }

   /**
    * Delete order item
    *
    * @param $checkinCode
    * @param $cartID
    **/
   public function cart_delete($checkinCode, $cartID)
   {
      Cart::where('checkin_code', $checkinCode)->where('id', $cartID)->orderby('id', 'desc')->delete();

      return response()->json([
         "success" => true,
         "message" => "Item removed from order",
      ]);
   }


   /**
    * Order history
    *
    * @param $checkinCode
    **/
   public function orders($checkinCode)
   {
      $checkin = checkin::where('code', $checkinCode)->first();
      $orders = Orders::join('users', 'users.user_code', '=', 'orders.user_code')
         ->where('customerID', $checkin->customer_id)
         ->orderby('orders.id', 'desc')
         ->get();

      return response()->json([
         "success" => true,
         "message" => "Customer Orders",
         "checkin_details" => $checkin,
         "orders" => $orders,
      ]);
   }
   public function userOrders(Request $request)
   {

      $orders = Orders::where('user_code', $request->user()->user_code)->with('distributor', 'Customer')->orderby('orders.id', 'desc')
         ->get();
      return response()->json([
         "success" => true,
         "message" => "All your Orders",
         "Data" => $orders,
      ]);
   }


   /**
    * Order details
    *
    * @param $checkinCode
    * @param $orderID
    **/
   public function order_details($orderID)
   {
      $order = Orders::join('users', 'users.user_code', '=', 'orders.user_code')->where('order_code', $orderID)->first();
      //$orderItems = Order_items::where('order_code',$order->checkin_code)->orderby('id','desc')->get();
      $orderCart = DB::select(
         'SELECT
         `id`,
         `productID`,
         `product_name`,
         `qty`,
         `price`,
         `amount`,
         `tax_rate`,
         `tax_value`,
         `discount`,
         `total_amount`,
         `note`,
         `userID`,
         `checkin_code`,
         `customer_account`,
         `created_at`,
         `updated_at`
     FROM
         `order_cart`
     WHERE
     `checkin_code`=?',
         [$order->checkin_code]
      );

      $checkin = checkin::join('users', 'users.user_code', '=', 'customer_checkin.user_code')
         ->where('code', $order->checkin_code)
         ->get();

      return response()->json([
         "success" => true,
         "message" => "Order Details",
         "order" => $order,
         "order_items" => $orderCart,
         "checkin" => $checkin,
      ]);
   }

   // //order payment
   // public function order_print($orderID){
   //    $order = Orders::where('order_id',$orderID)->first();
   //    $orderItems = Order_items::where('orderID',$orderID)->orderby('id','desc')->get();

   //    $pdf = PDF::loadView('templates/receipt', compact('order','orderItems'));

   // 	return $pdf->stream('receipt.pdf');
   // }

   /**
    * Order edit
    *
    * @param $checkinCode
    * @param $orderID
    **/
   public function order_edit($orderID)
   {
      $order = Orders::join('users', 'users.user_code', '=', 'orders.user_code')->where('order_code', $orderID)->first();
      $orderItems = Order_items::where('order_code', $orderID)->orderby('id', 'desc')->get();
      $checkin = checkin::join('users', 'users.user_code', '=', 'customer_checkin.user_code')
         ->where('code', $order->checkin_code)
         ->first();

      return response()->json([
         "success" => true,
         "message" => "Order Edit",
         "order" => $order,
         "order_items" => $orderItems,
         "checkin" => $checkin,
      ]);
   }

   /**
    * Order update
    *
    * @param $itemID
    * @bodyParam qty string required as qty
    * @bodyParam orderID string as orderID
    **/
   public function order_update(Request $request, $itemID)
   {
      $orderItem = Order_items::where('orderID', $request->orderID)->where('id', $itemID)->first();
      $orderItem->quantity = $request->qty;
      $orderItem->sub_total = $request->qty * $orderItem->selling_price;
      $orderItem->total_amount = $request->qty * $orderItem->selling_price;
      $orderItem->save();

      //update orderID
      $order = Orders::where('order_id', $request->orderID)->first();
      $order->price_total = $orderItem->sum('total_amount');
      $order->save();

      FacadesSession::flash('success', 'Order Updated successfully');

      return response()->json([
         "success" => true,
         "message" => "Order Updated successfully",
      ]);
   }


   /**
    * Delete order item
    *
    * @param $itemID
    **/
   public function order_delete_item($itemID)
   {
      $orderItem = Order_items::where('id', $itemID)->first();
      $orderItem->delete();

      //update orderID
      $order = Orders::where('order_id', $orderItem->orderID)->first();
      $order->price_total = $orderItem->sum('total_amount');
      $order->save();

      return response()->json([
         "success" => true,
         "message" => "Order item deleted successfully",
      ]);
   }

   /**
    * Order cancellation
    *
    * @bodyParam order_id int required as order_id
    **/
   public function order_cancellation(Request $request)
   {

      $order = Orders::where('order_id', $request->order_id)->first();
      $order->cancellation_reason = $request->cancellation_reason;
      $order->order_status = 'Order canceled';
      $order->save();

      return response()->json([
         "success" => true,
         "message" => "Order cancelled successfully",
      ]);
   }


   /**
    * Order edit reason
    *
    * @bodyParam order_id int required as order_id
    **/
   public function order_edit_reason(Request $request)
   {
      $reason = new Order_edit_reason;
      $reason->reason = $request->reason;
      $reason->order_id = $request->order_id;
      $reason->user_code = Auth::user()->user_code;
      $reason->save();

      return response()->json([
         "success" => true,
         "message" => "Reason saved",
      ]);
   }
   /**
    * latest stock allocation
    *
    * @bodyParam user_code
    **/
   public function latest_allocation($user_code)
   {
      $allocation = allocations::join(
         'inventory_allocated_items',
         'inventory_allocated_items.created_by',
         '=',
         'inventory_allocations.created_by'
      )
         ->join(
            'product_information',
            'product_information.id',
            '=',
            'inventory_allocated_items.product_code'
         )
         ->join(
            'product_price',
            'product_price.productID',
            '=',
            'inventory_allocated_items.product_code'
         )->select(
            'product_information.id',
            'product_information.product_name',
            'product_information.category',
            'product_information.brand',
            'product_information.sku_code',
            'product_price.selling_price as retailer_price',
            'product_price.buying_price as wholesale_price',
            'product_price.distributor_price as distributor_price',
            'inventory_allocated_items.allocation_code',
            'inventory_allocated_items.current_qty',
            'inventory_allocated_items.allocated_qty',
            'inventory_allocations.created_at'
         )->where('inventory_allocations.sales_person', $user_code)->groupBy("product_information.id")->get();
      info($allocation);

      return response()->json([
         "success" => true,
         "latest_allocated_item" => $allocation,
         "message" => "Reason saved",
      ]);
   }
   /**
    * stock allocation history
    *
    * @bodyParam user_code
    **/
   public function allocation_history($user_code)
   {
      $allocation = allocations::join(
         'inventory_allocated_items',
         'inventory_allocated_items.allocation_code',
         '=',
         'inventory_allocations.allocation_code'
      )->join('product_information', 'product_information.id', '=', 'inventory_allocated_items.product_code')->join('product_price', 'product_price.productID', '=', 'inventory_allocated_items.product_code')->select(
         'product_information.product_name',
         'product_information.brand',
         'product_information.sku_code',
         'product_price.buying_price',
         'product_price.selling_price',
         'inventory_allocated_items.allocation_code',
         'inventory_allocated_items.current_qty',
         'inventory_allocated_items.allocated_qty',
         'inventory_allocations.created_at'
      )->where('inventory_allocations.sales_person', $user_code)->get();

      return response()->json([
         "success" => true,
         "allocation_history" => $allocation,
         // "allocated_item" => $allocated_item,
         "message" => "Reason saved",
      ]);
   }
}
