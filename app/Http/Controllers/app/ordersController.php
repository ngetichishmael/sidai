<?php

namespace App\Http\Controllers\app;

use App\Models\activity_log;
use App\Models\Cart;
use App\Models\inventory\allocations;
use App\Models\inventory\items;
use App\Models\Orders as Order;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\suppliers\suppliers;
use App\Models\User;
use App\Models\Orders;
use App\Models\Delivery;
use App\Models\customers;
use App\Models\Order_items;
use App\Models\warehousing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Delivery_items;
use App\Models\order_payments;
use App\Http\Controllers\Controller;
use App\Models\products\product_price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ordersController extends Controller
{
   //orders
   public function index()
   {
      return view('app.orders.index');
   }
   public function pendingdeliveries()
   {
      return view('app.orders.pendingdeliveries');
   }
   public function distributororders()
   {
      return view('app.orders.distributororders');
   }
   public function pendingorders()
   {
      return view('app.orders.pendingorders');
   }

   public function makeOrder($id)
   {
      return view('app.orders.make', [
         'id' => $id,
      ]);
   }
   //order details
   public function details($code)
   {
      $order = Orders::where('order_code', $code)->first();
      $items = Order_items::where('order_code', $order->order_code)->get();
      $sub = Order_items::select('sub_total')->where('order_code', $order->order_code)->get();
      $total = Order_items::select('total_amount')->where('order_code', $order->order_code)->get();
      $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
      $id = $Customer_id->customerID;
      $test = customers::where('id', $id)->first();
      // dd($test->id);
      $payment = order_payments::where('order_id', $order->order_code)->first();
      // dd($payment);
      return view('app.orders.details', compact('order', 'items', 'test', 'payment', 'sub', 'total'));
   }
   public function distributordetails($code)
   {
      $order = Orders::where('order_code', $code)->first();
      // dd($code);
      $items = Order_items::where('order_code', $order->order_code)->get();
      $sub = Order_items::select('sub_total')->where('order_code', $order->order_code)->get();
      $total = Order_items::select('total_amount')->where('order_code', $order->order_code)->get();
      $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
      $id = $Customer_id->customerID;
      $test = customers::where('id', $id)->first();
      // dd($test->id);
      $payment = order_payments::where('order_id', $order->order_code)->first();
      // dd($payment);
      return view('app.orders.distributorsdetails', compact('order', 'items', 'test', 'payment', 'sub', 'total'));
   }
   public function pendingdetails($code)
   {
      $order = Orders::where('order_code', $code)->first();
      // dd($code);
      $items = Order_items::where('order_code', $order->order_code)->get();
      $sub = Order_items::select('allocated_subtotal')->where('order_code', $order->order_code)->get();
      $total = Order_items::select('allocated_totalamount')->where('order_code', $order->order_code)->get();
      $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
      $id = $Customer_id->customerID;
      $test = customers::where('id', $id)->first();
      $payment = order_payments::where('order_id', $order->order_code)->first();
      $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
         ->orWhereNull('status')
         ->orWhere('status', '')
         ->orderby('name', 'desc')->get();
      $account_types = User::whereNotIn('account_type', ['Customer', 'Admin'])->groupBy('account_type')->get();
      return view('app.orders.pendingdetails', compact('order','account_types', 'items', 'test', 'payment', 'distributors', 'sub', 'total'));
   }

   //allocation
   public function allocation($code)
   {
      $order = Orders::where('order_code', $code)->first();
      $items = Order_items::where('order_code', $order->order_code)->get();
      $users = User::orderby('name', 'desc')->get();
      $warehouses = warehousing::orderby('id', 'desc')->get();
      $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
         ->orWhereNull('status')
         ->orWhere('status', '')
         ->orderby('name', 'desc')->get();
      $account_types = User::whereNotIn('account_type', ['Customer', 'Admin'])->groupBy('account_type')->get();

      return view('app.orders.allocation', compact('order', 'items', 'users', 'warehouses', 'distributors','account_types'));
   }
   public function allocationwithoutstock($code)
   {
      $order = Orders::where('order_code', $code)->first();
      $items = Order_items::where('order_code', $order->order_code)->get();
      $users = User::orderby('name', 'desc')->get();
      $warehouses = warehousing::orderby('id', 'desc')->get();
      $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
         ->orWhereNull('status')
         ->orWhere('status', '')
         ->orderby('name', 'desc')->get();
      $account_types = User::whereNotIn('account_type', ['Customer', 'Admin'])->groupBy('account_type')->get();

      return view('app.orders.allocationwithoutstock', compact('order', 'items', 'users', 'warehouses', 'distributors','account_types'));
   }
   public function distributorschangeStatus( Request  $request, $code)
   {
      $orderStatus = $request->input('order_status');

      Orders::where('order_code', $code)->update(['order_status' => $orderStatus]);
      Delivery::where('order_code', $code)->update(['delivery_status' => $orderStatus]);

      Session::flash('success', 'Order Status Updated Successfully');
      return redirect()->back();
   }

   //create delivery
   public function allocateOrders(Request $request)
   {
      $this->validate($request, [
         'user' => 'required'
      ]);
      $supplierID = null;
      $totalSum=0;
      $quantity=0;
      if ($request->account_type === "distributors") {
         $distributor = suppliers::find($request->user);
         if ($distributor) {
            for ($i = 0; $i < count($request->allocate); $i++) {
               $pricing = product_price::whereId($request->item_code[$i])->first();
               $totalSum += $request->price[$i];
               Order_items::where('productID', $request->item_code[$i])
                  ->where('order_code', $request->order_code)
                  ->update([
                     "requested_quantity" => $request->requested[$i],
                     "allocated_quantity" => $request->allocate[$i],
                     "allocated_subtotal" => $request->price[$i],
                     "allocated_totalamount" => $request->price[$i],
                  ]);
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
            Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
            return redirect()->route('orders.pendingorders');
         }else{
            Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
            return redirect()->route('orders.pendingorders');
         }
      }

      $delivery = Delivery::updateOrCreate(
         [
            "business_code" => Str::random(20),
            "customer" => $request->customer,
            "order_code" => $request->order_code
         ],
         [
            "delivery_code" => Str::random(20),
            "allocated" => $request->user,
            "delivery_note" => $request->note,
            "delivery_status" => "Waiting acceptance",
            "Type" => "Warehouse",
            "created_by" => Auth::user()->user_code
         ]
      );
      for ($i = 0; $i < count($request->allocate); $i++) {
         $pricing = product_price::whereId($request->item_code[$i])->first();
         $totalSum += $request->price[$i];
         Delivery_items::updateOrCreate(
            [
               "business_code" => Auth::user()->business_code,
               "delivery_code" => $delivery->delivery_code,
               "productID" => $request->item_code[$i],
            ],
            [
               "selling_price" => $pricing->selling_price,
               "sub_total" => $request->price[$i],
//               "total_amount" => $pricing->selling_price * $request->allocate[$i],
               "total_amount" => $request->price[$i],
               "product_name" => $request->product[$i],
               "allocated_quantity" => $request->allocate[$i],
               "delivery_item_code" => Str::random(20),
               "requested_quantity" => $request->requested[$i],
               "created_by" => Auth::user()->user_code
            ]
         );

         Order_items::where('productID', $request->item_code[$i])
            ->where('order_code', $request->order_code)
            ->update([
               "requested_quantity" => $request->requested[$i],
               "allocated_quantity" => $request->allocate[$i],
               "allocated_subtotal" => $request->price[$i],
               "allocated_totalamount" => $request->price[$i],
            ]);

         $quantity +=1;
      }

      $order = Orders::where('order_code', $request->order_code)->first();
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
      $activityLog->action = 'Order allocated to user '. $request->name. ' Role '.$request->account_type.'';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      Session::flash('success', 'Delivery created and orders allocated to a user');
      return redirect()->route('orders.pendingorders');
   }
   //create delivery without stock
   public function allocateOrdersWithoutStock(Request $request)
   {
      $this->validate($request, [
         'user' => 'required'
      ]);
      $supplierID = null;
      $totalSum=0;
      $quantity=0;
      if ($request->account_type === "distributors") {
         $distributor = suppliers::find($request->user);
         if ($distributor) {
            for ($i = 0; $i < count($request->allocate); $i++) {
               $pricing = product_price::whereId($request->item_code[$i])->first();
               $totalSum += $request->price[$i];
               Order_items::where('productID', $request->item_code[$i])
                  ->where('order_code', $request->order_code)
                  ->update([
                     "requested_quantity" => $request->requested[$i],
                     "allocated_quantity" => $request->allocate[$i],
                     "allocated_subtotal" => $request->price[$i],
                     "allocated_totalamount" => $request->price[$i],
                  ]);
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
            Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
            return redirect()->route('orders.pendingorders');
         }else{
            Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
            return redirect()->route('orders.pendingorders');
         }
      }

      $delivery = Delivery::updateOrCreate(
         [
            "business_code" => Str::random(20),
            "customer" => $request->customer,
            "order_code" => $request->order_code
         ],
         [
            "delivery_code" => Str::random(20),
            "allocated" => $request->user,
            "delivery_note" => $request->note,
            "delivery_status" => "Waiting acceptance",
            "Type" => "Van_sale",
            "created_by" => Auth::user()->user_code
         ]
      );
      $allocateArray = $request->allocate ?? [];
      for ($i = 0; $i < count($allocateArray); $i++) {
         $pricing = product_price::whereId($request->item_code[$i])->first();
         $totalSum += $request->price[$i];
         Delivery_items::updateOrCreate(
            [
               "business_code" => Auth::user()->business_code,
               "delivery_code" => $delivery->delivery_code,
               "productID" => $request->item_code[$i],
            ],
            [
               "selling_price" => $pricing->selling_price,
               "sub_total" => $request->price[$i],
               "total_amount" => $request->price[$i],
               "product_name" => $request->product[$i],
               "allocated_quantity" => $allocateArray[$i],
               "delivery_item_code" => Str::random(20),
               "requested_quantity" => $request->requested[$i],
               "created_by" => Auth::user()->user_code
            ]
         );

         Order_items::where('productID', $request->item_code[$i])
            ->where('order_code', $request->order_code)
            ->update([
               "requested_quantity" => $request->requested[$i],
               "allocated_quantity" => $allocateArray[$i],
               "allocated_subtotal" => $request->price[$i],
               "allocated_totalamount" => $request->price[$i],
            ]);

         $quantity +=1;
      }

      $order = Orders::where('order_code', $request->order_code)->first();
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
      $activityLog->action = 'Order allocated to user '. $request->name. ' Role '.$request->account_type.'';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      Session::flash('success', 'Delivery created and orders allocated to a user');
      return redirect()->route('orders.pendingorders');
   }
   public function reAllocateOrders(Request $request)
   {
      $this->validate($request, [
         'user' => 'required',
      ]);
      $supplierID = null;
      $order_code = Str::random(20);
      $totalSum=0;
      if ($request->account_type === "distributors") {
         $distributor = suppliers::find($request->user);
         if ($distributor) {
            for ($i = 0; $i < count($request->allocate); $i++) {
               $pricing = product_price::whereId($request->item_code[$i])->first();
               $totalSum += $request->price[$i];
               Order_items::where('productID', $request->item_code[$i])
                  ->where('order_code', $request->order_code)
                  ->update([
                     "requested_quantity" => $request->requested[$i],
                     "allocated_quantity" => $request->allocate[$i],
                     "allocated_subtotal" => $request->price[$i],
                     "allocated_totalamount" => $request->price[$i],
                  ]);
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
            $activityLog->activity = 'Re-allocate an order to a Distributor';
            $activityLog->user_code = auth()->user()->user_code;
            $activityLog->section = 'Order Re-allocation';
            $activityLog->action = 'Order Re-allocated to distributor' . $distributor->name . ' ';
            $activityLog->userID = auth()->user()->id;
            $activityLog->activityID = $random;
            $activityLog->ip_address = "";
            $activityLog->save();
            Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
            return redirect()->route('orders.pendingdeliveries');
         }else{
            Session::flash('error', 'Something went wrong, Order could not be re-allocated to distributor');
            return redirect()->route('orders.pendingdeliveries');
         }
      }

      $delivery = Delivery::updateOrCreate(
         [
            "business_code" => Str::random(20),
            "customer" => $request->customer,
            "order_code" => $order_code
         ],
         [
            "delivery_code" => Str::random(20),
            "allocated" => $request->user,
            "delivery_note" => $request->note,
            "delivery_status" => "Waiting acceptance",
            "created_by" => Auth::user()->user_code
         ]
      );
      $user_code = $request->user()->user_code;
      $business_code = $request->user()->business_code;
      $random = Str::random(20);
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      for ($i = 0; $i < count($request->allocate); $i++) {
         $pricing = product_price::whereId($request->item_code[$i])->first();
         $totalSum += $request->price[$i];
         Delivery_items::updateOrCreate(
            [
               "business_code" => Auth::user()->business_code,
               "delivery_code" => $delivery->delivery_code,
               "productID" => $request->item_code[$i],
            ],
            [
               "selling_price" => $pricing->selling_price,
               "sub_total" => $request->price[$i],
//               "total_amount" => $pricing->selling_price * $request->allocate[$i],
               "total_amount" => $request->price[$i],
               "product_name" => $request->product[$i],
               "allocated_quantity" => $request->allocate[$i],
               "delivery_item_code" => Str::random(20),
               "requested_quantity" => $request->requested[$i],
               "created_by" => Auth::user()->user_code
            ]
         );
         $product = product_information::whereId($request->item_code[$i])->first();
         Cart::updateOrCreate(
            [
               'checkin_code' => Str::random(20),
               "order_code" => $random,
            ],
            [
               'productID' => $request->item_code[$i],
               "product_name" => $request->product[$i],
               "qty" => $request->allocate[$i],
               "price" =>  $pricing->selling_price,
               "amount" => $request->price[$i],
               "total_amount" => $request->price[$i],
               "userID" => $user_code,
            ]
         );
         Order::updateOrCreate(
            [
               'order_code' => $random,
            ],
            [
               'user_code' => $user_code,
               'customerID' => $request->customer,
               'price_total' => $request->product[$i],
               'balance' => $request->product[$i],
               'order_status' => 'Waiting Acceptance',
               'payment_status' => 'Pending Payment',
               'qty' => $request->allocate[$i],
               'supplierID'=>$sidai->id ?? 1,
               'discount' => $items["discount"] ?? "0",
               'reallocated_from_order' => $request->order_code,
               'order_type' => 'Pre Order',
               'delivery_date' => now(),
               'business_code' => $user_code,
               'updated_at' => now(),
            ]
         );
         Order_items::create([
            'order_code' => $random,
            'productID' => $request->item_code[$i],
            "product_name" => $request->product[$i],
            "sub_total" => $request->price[$i],
            "total_amount" => $request->price[$i],
            "allocated_quantity" => $request->allocate[$i],
            'quantity' => $request->allocate[$i],
            'selling_price' => $pricing->selling_price,
            'discount' => 0,
            'taxrate' => 0,
            'taxvalue' => 0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

         DB::table('orders_targets')
            ->where('user_code', $user_code)
            ->increment('AchievedOrdersTarget', $request->allocate[$i]);
      }
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Re-Allocate an order to a User';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Order Re-Allocation';
      $activityLog->action = 'Order re-allocated to user '. $request->name. ' Role '.$request->account_type.' ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      Session::flash('success', 'Orders re-allocated to the user');
      return redirect()->route('orders.pendingdeliveries');
   }
   public function delivery(Request $request)
   {
      $this->validate($request, [
         'user' => 'required',
         'warehouse' => 'required',

      ]);

      $delivery = Delivery::updateOrCreate(
         [
            "business_code" => Auth::user()->business_code,
            "customer" => $request->customer,
            "order_code" => $request->order_code
         ],
         [
            "delivery_code" => Str::random(20),
            "allocated" => $request->user,
            "delivery_note" => $request->note,
            "delivery_status" => "Delivered",
            "created_by" => Auth::user()->user_code
         ]
      );

      for ($i = 0; $i < count($request->allocate); $i++) {

         $pricing = product_price::whereId($request->item_code[$i])->first();
         Delivery_items::updateOrCreate(
            [
               "business_code" => Auth::user()->business_code,
               "delivery_code" => $delivery->delivery_code,
               "productID" => $request->item_code[$i],

            ],
            [
               "selling_price" => $pricing->selling_price,
               "sub_total" => $pricing->selling_price * $request->allocate[$i],
               "total_amount" => $pricing->selling_price * $request->allocate[$i],
               "product_name" => $request->product[$i],
               "allocated_quantity" => $request->allocate[$i],
               "delivery_item_code" => Str::random(20),
               "requested_quantity" => $request->requested[$i],
               "created_by" => Auth::user()->user_code
            ]
         );
         Order_items::where('productID', $request->item_code[$i])
            ->where('order_code', $request->order_code)
            ->update([
               "requested_quantity" => $request->product[$i]
            ]);
      }

      Session::flash('success', 'Delivery created and orders allocated');

      return redirect()->route('delivery.index');
   }
}
