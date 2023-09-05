<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewOrderNotificationJob;
use App\Models\activity_log;
use App\Models\Cart;
use App\Models\customer\checkin;
use App\Models\inventory\allocations;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\Orders as Order;
use App\Models\products\product_information;
use App\Models\Region;
use App\Models\suppliers\suppliers;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CheckingSaleOrderController extends Controller
{

   public function amount(Request $request, $checkinCode)
   {
      $checkin = checkin::where('code', $checkinCode)->first();
      $request = $request->collect();
      $total = 0;
      foreach ($request as $value) {
         $product = product_information::with('ProductPrice')
            ->where('id', $value["productID"])
            ->where('business_code', $checkin->business_code)
            ->first();
         $total_amount = $value["qty"] * $value["price"];
         $total += $total_amount;
      }
      return $total;
   }

   //Start Vansales
   public function VanSales(Request $request, $checkinCode, $random)
   {
      $checkin = $checkinCode;
      $user_code = $request->user()->user_code;
      $total = 0;
      $region = Region::where('id', $request->user()->region_id)->first();
      $regionCode = strtoupper(substr($region->name, 0, 3));
      //$orderCount = Orders::where('order_code', 'like', $regionCode . '%')->count() + 1;
      
      $latestOrder = Orders::where('order_code', 'like', $regionCode . '%')
	          ->orderBy('created_at', 'desc')
	      ->first();

      if ($latestOrder) {
	          // Extract the order number and increment it
	          $latestOrderNumber = intval(substr($latestOrder->order_code, 4));
	              $orderCount = $latestOrderNumber + 1;
	      } else {
	                  // No previous orders in this region, start from 1
	                      $orderCount = 1;
	                      }

      $orderNumber = str_pad($orderCount, 5, '0', STR_PAD_LEFT);
      $ordercode = $regionCode . '-' . $orderNumber;
      $request = $request->collect();
      //info("all infomation sent ".$request->all());
      if (isset($request[0]['cartItem']) && is_array($request[0]['cartItem'])) {
         foreach ($request[0]['cartItem'] as $value) {
		 info($value["productID"]);
            $quantity = $value['qty'] ?? 1;
            $price_total = $quantity * $value["price"];
            $total += $price_total;
            $product = product_information::with('ProductPrice')->where('id', $value["productID"])->first();
	    info($product);
	    Cart::updateOrCreate(
               [
                  'checkin_code' => $checkinCode,
                  "order_code" => $ordercode,
               ],
               [
                  'productID' => $value["productID"],
                  "product_name" => $product->product_name,
                  "qty" => $quantity,
                  "price" => $value["price"],
                  "amount" => $quantity * $value["price"],
                  "total_amount" => $quantity * $value["price"],
                  "userID" => $user_code,
               ]
            );
            $checkitems = DB::table('inventory_allocated_items')
               ->where('product_code', $value["productID"])->where('created_by', $user_code)
               ->decrement(
                  'allocated_qty',
                  $value["qty"],
                  [
                     'updated_at' => now(),
                  ]
               );

            Order::updateOrCreate(
               [

                  'order_code' => $ordercode,
               ],
               [
                  'user_code' => $user_code,
                  'customerID' => $checkinCode,
                  'price_total' => $total,
                  'balance' => $total,
                  'order_status' => 'Pending Delivery',
                  'payment_status' => 'Pending Payment',
                  'qty' => $quantity,
                  'discount' => $items["discount"] ?? "0",
                  'checkin_code' => $checkinCode,
                  'order_type' => 'Van sales',
                  'delivery_date' => now(),
                  'business_code' => auth::user()->business_code ?? $checkinCode,
                  'updated_at' => now(),
               ]
            );
            Order_items::create([
               'order_code' => $ordercode,
               'productID' => $value["productID"],
               'product_name' => $product->product_name,
               'quantity' => $quantity,
               'sub_total' => $quantity * $value["price"],
               'total_amount' => $quantity * $value["price"],
               'selling_price' => $value["price"],
               'discount' => $items["discount"] ?? "0",
               'taxrate' => 0,
               'taxvalue' => 0,
               'created_at' => now(),
               'updated_at' => now(),
            ]);
         }
      } else {
         return response()->json([
            "success" => false,
            "message" => "Could not perform the operation",
            "order_code" => $ordercode,
         ]);
      }

      $ativity_rand = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Product added to vansale order';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Vansale order';
      $activityLog->action = 'Vansale order made by' . auth()->user()->name . ' order code  ' . $ordercode;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $ativity_rand;
      $activityLog->ip_address = "";
      $activityLog->save();

      return response()->json([
         "success" => true,
         "message" => "Product added to order",
         "order_code" => $ordercode,
         "data" => $checkin,
      ]);
   }

   //End of Vansales
   //Start Vansales
   public function VanSales12(Request $request, $checkinCode, $random)
   {
      $amountRequest = $request;
      $checkin = checkin::where('code', $checkinCode)->first();
      $user_code = $request->user()->user_code;
      $requests = $request->collect();
      foreach ($requests as $items) {
         info("Van sales Cart Items");
         info($items);
         foreach ($items["cartItem"] as $value) {
            info("Van sales Cart Items");
            info($value);
            $product = product_information::with('ProductPrice')->where('id', (int)$value["productID"])->first();
            Cart::updateOrCreate(
               [
                  'checkin_code' => $checkinCode,
                  "order_code" => $random,
               ],
               [
                  'productID' => $value["productID"],
                  "product_name" => $product->product_name,
                  "qty" => $value["qty"],
                  "price" => $value["price"],
                  "amount" => $value["qty"] * $value["price"],
                  "total_amount" => $value["qty"] * $value["price"],
                  "userID" => $user_code,
               ]
            );
            DB::table('inventory_allocated_items')
               ->where('product_code', $value["productID"])
               ->decrement(
                  'allocated_qty',
                  $value["qty"],
                  [
                     'updated_at' => now(),
                  ]
               );
            Order::updateOrCreate(
               [

                  'order_code' => $random,
               ],
               [
                  'user_code' => $user_code,
                  'customerID' => $checkin->customer_id,
                  'price_total' => $value["qty"] * $value["price"],
                  'balance' => $value["qty"] * $value["price"],
                  'order_status' => 'Pending Delivery',
                  'payment_status' => 'Pending Payment',
                  'supplierID' => $request->distributor,
                  'qty' => $value["qty"],
                  'discount' => $items["discount"] ?? "0",
                  'checkin_code' => $checkinCode,
                  'order_type' => 'Van sales',
                  'delivery_date' => now(),
                  'business_code' => $checkin->business_code,
                  'updated_at' => now(),
               ]
            );
            Order_items::create([
               'order_code' => $random,
               'productID' => $value["productID"],
               'product_name' => $product->product_name,
               'quantity' => $value["qty"],
               'sub_total' => $value["qty"] * $value["price"],
               'total_amount' => $value["qty"] * $value["price"],
               'selling_price' => $value["price"],
               'discount' => $items["discount"] ?? "0",
               'taxrate' => 0,
               'taxvalue' => 0,
               'created_at' => now(),
               'updated_at' => now(),
            ]);
         }
      }
      return response()->json([
         "success" => true,
         "message" => "Product added to order",
         "order_code" => $random,
         "data" => $checkin,
      ]);
   }

   //End of Vansales

   // Beginning of NewSales
   public function NewSales(Request $request, $checkinCode, $random, $distributor)
   {

//       $checkin = customers::whereId($checkinCode)->first();
      $region = Region::where('id', auth()->user()->region_id)->first();
      $regionCode = strtoupper(substr($region->name, 0, 3));
     // $order_get = Orders::where('order_code', 'like', $regionCode . '%')->orderBy("id", "desc")->first();
   $latestOrder = Orders::where('order_code', 'like', $regionCode . '%')
	       ->orderBy('created_at', 'desc')
           ->first();

      if ($latestOrder) {
	        $latestOrderNumber = intval(substr($latestOrder->order_code, 4));
	               $orderCount = $latestOrderNumber + 1;
	               } else {
	                   // No previous orders in this region, start from 1
	                      $orderCount = 1;
	                       }
      $orderNumber = str_pad($orderCount, 5, '0', STR_PAD_LEFT);
      $ordercode = $regionCode . '-' . $orderNumber;
     
//      if (empty($orderCode)){
//         $orderCode = Helper::generateRandomString(8);
//      }
//      $orderCode = Helper::generateRandomString(8);
      $user_code = $request->user()->user_code;
      $request1 = $request->collect();
      $total = 0;
      info($request1);
//        $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      foreach ($request1 as $value) {
         $price_total = $value["qty"] * $value["price"];
         $total += $price_total;
         $product = product_information::whereId($value["productID"])->first();
			info($product);      
	 Cart::updateOrCreate(
            [
               'checkin_code' => Str::random(20),
               "order_code" => $ordercode,
            ],
            [
               'productID' => $value["productID"],
               "product_name" => $product->product_name,
               "qty" => $value["qty"],
               "price" => $value["price"],
               "amount" => $value["qty"] * $value["price"],
               "total_amount" => $value["qty"] * $value["price"],
               "userID" => $user_code,
            ]
         );
         $order = Order::updateOrCreate(
            [
               'order_code' => $ordercode,
            ],
            [
               'user_code' => $user_code,
               'customerID' => $checkinCode,
               'price_total' => $total,
               'balance' => $total,
               'order_status' => 'Pending Delivery',
               'payment_status' => 'Pending Payment',
               'qty' => $value["qty"],
               'supplierID' => $distributor ?? 1,
               'discount' => $items["discount"] ?? "0",
               'checkin_code' => $checkinCode,
               'order_type' => 'Pre Order',
               'delivery_date' => now(),
               'business_code' => $user_code,
               'updated_at' => now(),
            ]
         );
         Order_items::create([
            'order_code' => $ordercode,
            'productID' => $value["productID"],
            'product_name' => $product->product_name,
            'quantity' => $value["qty"],
            'sub_total' => $value["qty"] * $value["price"],
            'total_amount' => $value["qty"] * $value["price"],
            'selling_price' => $value["price"],
            'discount' => 0,
            'taxrate' => 0,
            'taxvalue' => 0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

         DB::table('orders_targets')
            ->where('user_code', $user_code)
            ->increment('AchievedOrdersTarget', $value["qty"]);
      }
      if ($distributor != 1 && $distributor != null) {
//            $usersToNotify = Suppliers::findOrFail($distributor);
//            $number = $usersToNotify->phone_number;
//            $order_code = $random;
//            $this->sendOrder($number, $order_code);

         $usersToNotify = Suppliers::findOrFail($distributor);
         $number = $usersToNotify->phone_number;
         $order_code = $ordercode;
         $this->sendOrder($number, $order_code);
         $distributor = $usersToNotify->name;
//           $distributorid = $usersToNotify->id;
         $sales = auth()->user()->name;
         $sales_number = auth()->user()->phone_number;

         Notification::send($usersToNotify, new NewOrderNotification($order, $distributor, $sales, $sales_number));
         // SendNewOrderNotificationJob::dispatchAfterResponse($order, $distributor, $distributorid, $sales, $sales_number);
      }
      $ativity_rand = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Product added to order';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'New sales order';
      $activityLog->action = 'Newsales order made by' . Auth::user()->name . ' order code  ' . $ordercode;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $ativity_rand;
      $activityLog->ip_address = "";
      $activityLog->save();
      return response()->json([
         "success" => true,
         "message" => "Product added to order",
         "order_code" => $ordercode,
         "data" => null,
      ]);
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
