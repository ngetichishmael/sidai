<?php

namespace App\Http\Controllers\app\customer;

use App\Http\Controllers\Controller;
use App\Models\business;
use App\Models\Cart;
use App\Models\customer\checkin;
use App\Models\customer\customers;
use App\Models\Order_edit_reason;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use Auth;
use Helper;
use Illuminate\Http\Request;
use PDF;
use Session;

class checkinController extends Controller
{
   //checkin
   public function calculate_distance(Request $request){
      $customer = customers::where('account',$request->customer)->where('businessID',Auth::user()->businessID)->first();
      $lat1 = $customer->latitude;
      $lon1 = $customer->longitude;
      $lat2 = $request->latitude;
      $lon2 = $request->longitude;
      $unit = "K";

      $distance = round(Helper::distance($lat1, $lon1, $lat2, $lon2, $unit),2);

      // if($distance < 0.05){

         //create a check in session
         $checkin = new checkin;
         $checkin->code = Helper::generateRandomString(20);
         $checkin->customer_id =  $customer->id;
         $checkin->account_number = $request->customer;
         $checkin->checkin_type = $request->checkin_type;
         $checkin->user_id = Auth::user()->id;
         $checkin->ip = Helper::get_client_ip();
         $checkin->start_time = date('H:i:s');
         $checkin->businessID = Auth::user()->businessID;
         $checkin->save();

         //recorord activity
         $activities = '<b>'.Auth::user()->name.'</b> Has <b>Checked-in</b> to <i> '.$customer->customer_name.'</i> @ '.date('H:i:s');
         $section = 'Customer';
         $action = 'Checkin';
         $businessID = Auth::user()->businessID;
         $activityID = $checkin->code;

		   Helper::activity($activities,$section,$action,$activityID,$businessID);

         return redirect()->route('customer.checkin',$checkin->code);

      // }else{
      //    Session::flash('warning','You are not near the customer shop');
      //    return redirect()->back();
      // }

   }

   //checkin
   public function checkin($code){
      $checkin = checkin::where('code',$code)->first();
      $customer = customers::where('account',$checkin->account_number)->where('businessID',Auth::user()->businessID)->first();
      return view('app.checkin.dashboard', compact('checkin','customer','code'));
   }

   //stock
   public function stock($code){
      $cartTotal = Cart::where('checkin_code',$code)->count();
      return view('app.checkin.stock', compact('code','cartTotal'));
   }

   //chekout
   public function checkout($code){
      //check if cart has items
      $cart = Cart::where('checkin_code',$code)->orderby('id','desc')->count();
      if($cart != 0){
         Session::flash('warning','You still have order in your cart');

         return redirect()->route('customer.checkin',$code);
      }

      $checkin = checkin::where('code',$code)->first();
      $checkin->stop_time = date('H:i:s');
      $checkin->save();

      $customer = customers::where('account',$checkin->account_number)->where('businessID',Auth::user()->businessID)->first();

      //recorord activity
      $activities = '<b>'.Auth::user()->name.'</b> Has <b>Checked-out</b> to <i> '.$customer->customer_name.'</i> @ '.date('H:i:s');
      $section = 'Customer';
      $action = 'Checkin';
      $businessID = Auth::user()->businessID;
      $activityID = $checkin->code;

      Helper::activity($activities,$section,$action,$activityID,$businessID);

      Session::flash('success','You have checked out');

      return redirect()->route('app.dashboard');
   }

   //add_to_cart
   public function add_to_cart(Request $request, $code){
      $this->validate($request,[
         'productID' => 'required',
         'qty' => 'required',
      ]);

      $checkin = checkin::where('code',$code)->first();

      $product = product_information::join('product_price','product_price.productID','=','product_information.id')
                                    ->where('product_information.id',$request->productID)
                                    ->where('product_information.businessID',Auth::user()->businessID)
                                    ->first();

      $checkInCart = Cart::where('checkin_code',$code)->where('productID',$request->productID)->count();

      if($checkInCart > 0){
         $cart = Cart::where('checkin_code',$code)->where('productID',$request->productID)->first();
         $cart->qty = $request->qty;
         $cart->price = $product->selling_price;
         $cart->amount = $request->qty * $product->selling_price;
         $cart->total_amount = $request->qty * $product->selling_price;
         $cart->userID = Auth::user()->id;
         $cart->save();
      }else{
         $cart = new Cart;
         $cart->productID = $request->productID;
         $cart->product_name = $product->product_name;
         $cart->qty = $request->qty;
         $cart->price = $product->selling_price;
         $cart->amount = $request->qty * $product->selling_price;
         $cart->userID = Auth::user()->id;
         $cart->customer_account = $checkin->account_number;
         $cart->total_amount = $request->qty * $product->selling_price;
         $cart->checkin_code = $code;
         $cart->save();
      }

      Session::flash('success','Product added to order');

      return redirect()->back();

   }

   //cart
   public function cart($code){
      $products = Cart::where('checkin_code',$code)->orderby('id','desc')->get();

      return view('app.checkin.cart', compact('code','products'));
   }

   //save order
   public function save_order(Request $request, $code){

      $this->validate($request, [
         'order_type' => 'required',
      ]);

      //checkin details
      $checkin = checkin::where('code',$code)->first();


      //get cart items
      $cart = Cart::where('checkin_code',$code)->get();

      $orderCode = Helper::generateRandomString(8);
      //order
      $order = new Orders;
      $order->order_id =  $orderCode;
      $order->userID = Auth::user()->id;
      $order->customerID = $checkin->customer_id;
      $order->checkin_code = $code;
      $order->price_total = $cart->sum('amount');
      $order->order_status = 'Pending Delivery';
      $order->payment_status = 'Pending Payment';
      $order->qty = $cart->sum('qty');
      $order->order_type = $request->order_type;
      $order->balance = $cart->sum('amount');
      $order->delivery_date = $request->delivery_date;
      $order->reasons_partial_delivery = $request->reasons_partial_delivery;
      $order->save();

      //create order items
      foreach($cart as $item){
         $cartItem =  Cart::where('checkin_code',$code)->where('id',$item->id)->first();

         $orderItems = new Order_items;
         $orderItems->orderID =  $orderCode;
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

      Session::flash('success','Order created successfully');

      return redirect()->route('checkin.order.details',[$code,$orderCode]);
   }

   //delete order item
   public function cart_delete($code,$id){
      Cart::where('checkin_code',$code)->where('id',$id)->orderby('id','desc')->delete();

      Session::flash('success','Item removed from order');

      return redirect()->back();
   }

   //order history
   public function orders($code){
      $checkin = checkin::where('code',$code)->first();
      $orders = Orders::join('users','users.id','=','orders.userID')
                     ->where('customerID',$checkin->customer_id)
                     ->orderby('orders.id','desc')
                     ->get();

      return view('app.checkin.orders.index',compact('code','orders'));
   }

   //order details
   public function order_details($code,$orderID){
      $order = Orders::join('users','users.id','=','orders.userID')->where('order_id',$orderID)->first();
      $orderItems = Order_items::where('orderID',$orderID)->orderby('id','desc')->get();
      $checkin = checkin::join('users','users.id','=','customer_checkin.user_id')
                        ->where('code',$order->checkin_code)
                        ->first();
      $business = business::where('businessID',Auth::user()->businessID)->first();

      return view('app.checkin.orders.details',compact('code','order','orderItems','checkin','business'));
   }

   //order payment
   public function order_print($code,$orderID){
      $order = Orders::where('order_id',$orderID)->first();
      $orderItems = Order_items::where('orderID',$orderID)->orderby('id','desc')->get();

      $pdf = PDF::loadView('templates/receipt', compact('order','orderItems'));

		return $pdf->stream('receipt.pdf');
   }

   //order edit
   public function order_edit($code,$orderID){
      $order = Orders::join('users','users.id','=','orders.userID')->where('order_id',$orderID)->first();
      $orderItems = Order_items::where('orderID',$orderID)->orderby('id','desc')->get();
      $checkin = checkin::join('users','users.id','=','customer_checkin.user_id')
                        ->where('code',$order->checkin_code)
                        ->first();
      $business = business::where('businessID',Auth::user()->businessID)->first();

      return view('app.checkin.orders.edit',compact('code','order','orderItems','checkin','business'));
   }

   //order update
   public function order_update(Request $request,$code,$itemID){
      $orderItem = Order_items::where('orderID',$request->orderID)->where('id',$itemID)->first();
      $orderItem->quantity = $request->qty;
      $orderItem->sub_total = $request->qty * $orderItem->selling_price;
      $orderItem->total_amount = $request->qty * $orderItem->selling_price;
      $orderItem->save();

      //update orderID
      $order = Orders::where('order_id',$request->orderID)->first();
      $order->price_total = $orderItem->sum('total_amount');
      $order->save();

      Session::flash('success','Order Updated successfully');

      return redirect()->back();
   }

   //order_delete_item
   public function order_delete_item($code,$itemID){
      $orderItem = Order_items::where('id',$itemID)->first();
      $orderItem->delete();

      //update orderID
      $order = Orders::where('order_id',$orderItem->orderID)->first();
      $order->price_total = $orderItem->sum('total_amount');
      $order->save();

      Session::flash('success','Order item deleted successfully');

      return redirect()->back();
   }

   //order cancellation
   public function order_cancellation(Request $request){

      $order = Orders::where('order_id',$request->order_id)->first();
      $order->cancellation_reason = $request->cancellation_reason;
      $order->order_status = 'Order canceled';
      $order->save();

      Session::flash('success','Order cancelled successfully');

      return redirect()->back();
   }

   //Order edit reason
   public function order_edit_reason(Request $request, $code){
      $reason = new Order_edit_reason;
      $reason->reason = $request->reason;
      $reason->order_id = $request->order_id;
      $reason->user_id = Auth::user()->id;
      $reason->save();

      return redirect()->route('checkin.order.edit',[$code,$request->order_id]);
   }
}

