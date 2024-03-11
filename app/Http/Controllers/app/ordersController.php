<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\Cart;
use App\Models\customers;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\Order_items;
use App\Models\order_payments;
use App\Models\Orders;
use App\Models\Orders as Order;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\suppliers\suppliers;
use App\Models\User;
use App\Models\warehousing;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use PDF;

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
    public function vansaleorders()
    {
        return view('app.orders.vansaleorders');
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
        $order = Orders::where('order_code', $code)->with('User')->first();
        $items = Order_items::where('order_code', $order->order_code)
           ->with(['productInformation' => function ($query) {
              $query->select('id', 'product_name', 'sku_code');
           }])
           ->get();
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
    public function vansaledetails($code)
    {
        $order = Orders::where('order_code', $code)->with('User')->first();
        $items = Order_items::where('order_code', $order->order_code)->get();
       $sub = Order_items::select('sub_total')->where('order_code', $order->order_code)->get();
       $total = Order_items::select('total_amount')->where('order_code', $order->order_code)->get();
        $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
        $id = $Customer_id->customerID;
        $test = customers::where('id', $id)->first();
        $payment = order_payments::where('order_id', $order->order_code)->first();
        return view('app.orders.vansaledetails', compact('order', 'sub', 'total','items', 'test', 'payment'));
    }
    public function distributordetails($code)
    {
        $order = Orders::where('order_code', $code)->first();
        // dd($code);
        $items = Order_items::where('order_code', $order->order_code)->get();
        $sub = Order_items::select('sub_total')->where('order_code', $order->order_code)->get();
        $total = Order_items::select('total_amount')->where('order_code', $order->order_code)->get();
        $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
        $test = customers::where('id', $Customer_id->customerID)->first();
        // dd($test->id);
        $payment = order_payments::where('order_id', $order->order_code)->first();
        // dd($payment);
       $di=suppliers::find($order->supplierID);
       $distributor=$di->name;
        return view('app.orders.distributorsdetails', compact('order', 'distributor', 'items', 'test', 'payment', 'sub', 'total'));
    }
    public function pendingdetails($code)
    {
        $order = Orders::where('order_code', $code)->first();
        // dd($code);
        $items = Order_items::where('order_code', $order->order_code)
           ->with(['productInformation' => function ($query) {
              $query->select('id', 'product_name', 'sku_code');
           }])
           ->get();
        //dd($items);
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
        return view('app.orders.pendingdetails', compact('order', 'account_types', 'items', 'test', 'payment', 'distributors', 'sub', 'total'));
    }

    //allocation
    public function allocation($code)
    {
        $order = Orders::where('order_code', $code)->first();
        $items = Order_items::where('order_code', $order->order_code)->get();
        $users = User::orderby('name', 'desc')->get();
        $warehouses = warehousing::orderby('id', 'desc')->get();
        $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['Sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
            ->orWhereNull('status')
            ->orWhere('status', '')
            ->orderby('name', 'desc')->get();
       $warehouseCodes = $items->pluck('productInformation.warehouse_code')->toArray();

       $warehouse = warehousing::whereIn('warehouse_code', $warehouseCodes)->first();
        $account_types = User::whereNotIn('account_type', ['Customer', 'Admin'])->groupBy('account_type')->get();

        return view('app.orders.allocation', compact('order', 'items', 'users', 'warehouse', 'distributors', 'account_types'));
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

        return view('app.orders.allocationwithoutstock', compact('order', 'items', 'users', 'warehouses', 'distributors', 'account_types'));
    }
    public function distributorschangeStatus2(Request $request, $code)
    {
        $orderStatus = $request->input('order_status');
        Orders::where('order_code', $code)->update(['order_status' => $orderStatus]);
        Delivery::where('order_code', $code)->update(['delivery_status' => $orderStatus]);
        Session::flash('success', 'Order Status Updated Successfully');
        return redirect()->back();
    }
   public function distributorschangeStatus(Request $request, $code)
   {
      $orderStatus = $request->input('order_status') ?? $request->input('order_status1');
      if ($orderStatus == 'Disapproved') {
         $disapprovalReason = $request->input('disapproval_reason');
         Orders::where('order_code', $code)->update([
            'order_status' => $orderStatus,
            'rejection_reasons' => $disapprovalReason,
            'approved_by'=>$request->user()->id
            ]);
         Delivery::where('order_code', $code)->update([
            'delivery_status' => $orderStatus,
         ]);
      } else {
         Orders::where('order_code', $code)->update(['order_status' => $orderStatus]);
         Delivery::where('order_code', $code)->update(['delivery_status' => $orderStatus]);
      }
      Session::flash('success', 'Order Status Updated Successfully');
      return redirect()->back();
   }


   //create delivery
    public function allocateOrders(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
        ]);
        $supplierID = null;
        $totalSum = 0;
        $quantity = 0;
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
                        "price_total" => $totalSum,
                        "balance" => $totalSum,
                    ]);

                $random = Str::random(20);
                $activityLog = new activity_log();
                $activityLog->activity = 'Allocate an order to a Distributor';
                $activityLog->user_code = auth()->user()->user_code;
                $activityLog->section = 'Web';
                $activityLog->action = 'Order allocated to distributor' . $distributor->name . ' ';
                $activityLog->userID = auth()->user()->id;
                $activityLog->activityID = $random;
                $activityLog->ip_address = "";
                $activityLog->save();
                Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
                return redirect()->route('orders.pendingorders');
            } else {
                Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
                return redirect()->route('orders.pendingorders');
            }
        }
       for ($i = 0; $i < count($request->allocate); $i++) {
          $check = product_inventory::where('productID', $request->item_code[$i])->first();
          if ($check->current_stock < $request->allocate[$i]) {
             Session()->flash('error', 'Current stock ' . $check->current_stock . ' is less than your allocation quantity of ' .$request->allocate[$i]);
             return Redirect::back();
          }
       }
        $delivery = Delivery::updateOrCreate(
            [
                "business_code" => Str::random(20),
                "customer" => $request->customer,
                "order_code" => $request->order_code,
            ],
            [
                "delivery_code" => Str::random(20),
                "allocated" => $request->user,
                "delivery_note" => $request->note,
                "delivery_status" => "Waiting acceptance",
                "Type" => "Warehouse",
                "created_by" => Auth::user()->user_code,
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
                    "created_by" => Auth::user()->user_code,
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

            $quantity += 1;
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
        $rdm = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Allocate an order to a User';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Web';
        $activityLog->action = 'Order allocated to user ' . $request->name . ' Role ' . $request->account_type . '';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $rdm;
        $activityLog->ip_address = "";
        $activityLog->save();
        Session::flash('success', 'Delivery created and orders allocated to a user');
        return redirect()->route('orders.pendingorders');
    }
    //create delivery without stock
    public function allocateOrdersWithoutStock(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
        ]);
        $supplierID = null;
        $totalSum = 0;
        $quantity = 0;
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
                        "price_total" => $totalSum,
                        "balance" => $totalSum,
                    ]);

                $random = Str::random(20);
                $activityLog = new activity_log();
                $activityLog->activity = 'Allocate an order to a Distributor';
                $activityLog->user_code = auth()->user()->user_code;
                $activityLog->section = 'Web';
                $activityLog->action = 'Order allocated to distributor' . $distributor->name . ' ';
                $activityLog->userID = auth()->user()->id;
                $activityLog->activityID = $random;
                $activityLog->ip_address = "";
                $activityLog->save();
                Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
                return redirect()->route('orders.pendingorders');
            } else {
                Session::flash('error', 'Something went wrong, Order could not be allocated to distributor');
                return redirect()->route('orders.pendingorders');
            }
        }

        $delivery = Delivery::updateOrCreate(
            [
                "business_code" => Str::random(20),
                "customer" => $request->customer,
                "order_code" => $request->order_code,
            ],
            [
                "delivery_code" => Str::random(20),
                "allocated" => $request->user,
                "delivery_note" => $request->note,
                "delivery_status" => "Waiting acceptance",
                "Type" => "Van_sale",
                "created_by" => Auth::user()->user_code,
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
                    "total_amount" => $request->price[$i],
                    "product_name" => $request->product[$i],
                    "allocated_quantity" => $request->allocate[$i],
                    "delivery_item_code" => Str::random(20),
                    "requested_quantity" => $request->requested[$i],
                    "created_by" => Auth::user()->user_code,
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

            $quantity += 1;
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
        $activityLog->section = 'Web';
        $activityLog->action = 'Order allocated to user ' . $request->name . ' Role ' . $request->account_type . '';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();
        Session::flash('success', 'Delivery created and orders allocated to a user');
        return redirect()->route('orders.pendingorders');
    }
    public function reAllocateOrders(Request $request)
    {
//       dd($request->all());
        $this->validate($request, [
            'user' => 'required',
        ]);
        $supplierID = null;
        $order_code = Str::random(20);
//        info("order code generated ".$order_code);
        $totalSum = 0;
       $business_code = $request->user()->business_code;
//       dd($request->all());
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
                        "price_total" => $totalSum,
                        "balance" => $totalSum,
                    ]);

                $random = Str::random(20);
                $activityLog = new activity_log();
                $activityLog->activity = 'Re-allocate an order to a Distributor';
                $activityLog->user_code = auth()->user()->user_code;
                $activityLog->section = 'Web';
                $activityLog->action = 'Order Re-allocated to distributor' . $distributor->name . ' ';
                $activityLog->userID = auth()->user()->id;
                $activityLog->activityID = $random;
                $activityLog->ip_address = "";
                $activityLog->save();
                Session::flash('success', 'Order allocated to distributor ' . $distributor->name);
                return redirect()->route('orders.pendingdeliveries');
            } else {
                Session::flash('error', 'Something went wrong, Order could not be re-allocated to distributor');
                return redirect()->route('orders.pendingdeliveries');
            }
        }
       for ($i = 0; $i < count($request->allocate); $i++) {
          $check = product_inventory::where('productID', $request->item_code[$i])->first();
          info("check product ".$check);
          if ($check->current_stock < $request->allocate[$i]) {
             Session()->flash('error', 'Current stock ' . $check->current_stock . ' is less than your allocation quantity of ' .$request->allocate[$i]);
             return Redirect::back();
          }
       }
        $delivery = Delivery::updateOrCreate(
            [
                "business_code" => $business_code,
                "customer" => $request->customer,
                "order_code" => $order_code,
            ],
            [
                "delivery_code" => Str::random(20),
                "allocated" => $request->user,
                "delivery_note" => $request->note,
                "delivery_status" => "Waiting acceptance",
                "created_by" => Auth::user()->user_code,
            ]
        );
//       info("delivery ".$delivery);
        $user_code = $request->user()->user_code;
        $random = $order_code;
        $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
        for ($i = 0; $i < count($request->allocate); $i++) {
            $pricing = product_price::whereId($request->item_code[$i])->first();
            info("pricing ".$pricing);
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
                    "created_by" => Auth::user()->user_code,
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
                    "price" => $pricing->selling_price,
                    "amount" => $request->price[$i],
                    "total_amount" => $request->price[$i],
                    "userID" =>  $request->user,
                ]
            );
            Order::updateOrCreate(
                [
                    'order_code' => $random,
                ],
                [
                    'user_code' =>  $request->user,
                    'customerID' => $request->customer,
                    'price_total' => $request->price[$i],
                    'balance' => $request->price[$i],
                    'order_status' => 'Waiting Acceptance',
                    'payment_status' => 'Pending Payment',
                    'qty' => $request->allocate[$i],
                    'supplierID' => $sidai->id ?? 1,
                    'discount' => $request->discount ?? "0",
                    'reallocated_from_order' => $request->order_code,
                    'order_type' => 'Pre Order',
                    'delivery_date' => now(),
                    'business_code' => $request->user()->business_code,
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
        $rand = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Re-Allocate an order to a User';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Web';
        $activityLog->action = 'Order re-allocated '.$random.' to user ' . $request->name . ' Role ' . $request->account_type . ' ';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $rand;
        $activityLog->ip_address = "";
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
                "order_code" => $request->order_code,
            ],
            [
                "delivery_code" => Str::random(20),
                "allocated" => $request->user,
                "delivery_note" => $request->note,
                "delivery_status" => "Delivered",
                "created_by" => Auth::user()->user_code,
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
                    "created_by" => Auth::user()->user_code,
                ]
            );
            Order_items::where('productID', $request->item_code[$i])
                ->where('order_code', $request->order_code)
                ->update([
                    "requested_quantity" => $request->product[$i],
                ]);
        }

        Session::flash('success', 'Delivery created and orders allocated');

        return redirect()->route('delivery.index');
    }

    public function renotify($order, $distributor){
       $usersToNotify = Suppliers::findOrFail($distributor);
       $number = $usersToNotify->phone_number;
       $order_code = $order;
       $this->sendOrder($number, $order_code);
       $distributor = $usersToNotify->name;
       $sales = auth()->user()->name;
       $sales_number = auth()->user()->phone_number;

       Notification::send($usersToNotify, new NewOrderNotification($order_code, $distributor, $sales, $sales_number));
   return redirect()->route('orders.distributororders')->with('success', 'Distributor Notification sent successful');
    }

   public function generatePDF(Request $request)
   {
      $order_status='';
      if(strtolower($request->order_status) == "pending delivery") {
         $order_status="Pending Order";
      }elseif(strtolower($request->order_status) == "complete delivery" || strtolower($request->order_status) == "delivered") {
         $order_status="Order Derivered";
      } else { $order_status=$request->order_status; }
      $data = [
         'test' => $request->input('test'),
         'order' => json_decode($request->input('order'), true),
         'items' => json_decode($request->input('items'), true),
         'sub' => $request->input('sub'),
         'total' => $request->input('total'),
         'order_status' => $order_status,
         'distributor' => $request->distributor
      ];

      $pdf = PDF::loadView('Exports.distributororderdetails_pdf', $data);

      return $pdf->download('distributororderdetails_pdf.pdf');
   }
   public function generateOrderPDF(Request $request)
   {
      $order_status='';
      if(strtolower($request->order_status) == "pending delivery") {
         $order_status="Pending Order";
      }elseif(strtolower($request->order_status) == "complete delivery" || strtolower($request->order_status) == "delivered") {
         $order_status="Order Derivered";
      } else { $order_status=$request->order_status; }
      $data = [
         'test' => $request->input('test'),
         'order' => json_decode($request->input('order'), true),
         'items' => json_decode($request->input('items'), true),
         'sub' => $request->input('sub'),
         'total' => $request->input('total'),
         'order_status' => $order_status,
         'distributor' => $request->distributor
      ];
      $order_code = $data['order']['order_code'] ?? null;
      $pdf = PDF::loadView('Exports.orderdetails_pdf', $data);
      return $pdf->download('order ' . $order_code . '.pdf', ['Content-Disposition' => 'attachment']);
//      return $pdf->download('order '.$order_code.'.pdf');
   }

   public function sendOrder($number, $order_code)
   {
      if ($number != null) {
         try {
            $curl = curl_init();

            $url = 'https://accounts.jambopay.com/auth/token';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/x-www-form-urlencoded',
               )
            );

            curl_setopt($curl, CURLOPT_POSTFIELDS,
               http_build_query(array('grant_type' => 'client_credentials', 'client_id' => config('services.jambopay.sms_client_id'), 'client_secret' => config('services.jambopay.sms_client_secret'))));

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);

            $token = json_decode($curl_response);
            curl_close($curl);

            $curl = curl_init();

            $message = 'You have a new Sidai order ' . $order_code . '. Order details sent to your email';
            curl_setopt_array($curl, array(
               CURLOPT_URL => 'https://swift.jambopay.co.ke/api/public/send',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'POST',
               CURLOPT_POSTFIELDS => json_encode(
                  array(
                     "sender_name" => "SOKOFLOW",
                     "contact" => $number,
                     "message" => $message,
                     "callback" => "https://pasanda.com/sms/callback",
                  )
               ),
               CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                  'Authorization: Bearer ' . $token->access_token,
               ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
         } catch (ExceptionHandler $e) {
            return response()->json(['message' => 'Error occurred while trying to send OTP code']);
         }
      } else {
         return response()->json(['message' => 'User is not registered!']);
      }
   }
}
