<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\Cart;
use App\Models\customer\checkin;
use App\Models\customer\customer_group;
use App\Models\customer\customers;
use App\Models\customer_groups;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\Order_items;
use App\Models\order_payments;
use App\Models\Orders;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\Route_customer;
use App\Models\Routes;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * @group Customers Api's
 *
 * APIs to manage the Customers
 * */
class customersController extends Controller
{
   /**
    * Customer list
    *
    * @param $businessCode
    * @queryParam page_size int size per page. Default to 20
    **/
   public function index(Request $request, $businessCode)
   {
      $user = $request->user();
      $query = customers::whereNotNull('latitude')
         ->whereNotNull('longitude')
         ->orderBy('id', 'DESC')
         ->get();

      return response()->json([
         "user" => $user,
         "success" => true,
         "message" => "Customer List",
         "data" => $query,
      ]);
   }

   /**
    * Customer details
    *
    * @param int $id this is the customer unique code
    **/
   public function details($id)
   {
      $customer = customers::find($id);

      return response()->json([
         "success" => true,
         "message" => "Customer List",
         "data" => $customer
      ]);
   }

   /**
    * Add Customer
    *
    * @bodyParam customer_name string required as outlet name
    * @bodyParam contact_person string required
    * @bodyParam phone_number string required
    * @bodyParam email string
    * @bodyParam address string
    * @bodyParam latitude string
    * @bodyParam longitude string
    * @bodyParam business_code string required
    * @bodyParam created_by string required user code
    **/

   public function customerprofile(Request $request){
      $user_code=$request->user()->user_code;
      $customer=customers::where('user_code', $user_code)->first();
      return response()->json([
         "success" => true,
         "message" => "Customer Profile",
         "profile" => $customer
      ]);
   }
public function RequestToBeCreditor(Request $request){
   $customer = customers::where('id', $request->customer_id)->first();
   if ($customer){
      customers::whereId($customer->id)->update([ 'is_creditor'=> 1,
         'creditor_status' => "waiting_approval" ]);
      return response()->json([
         "success" => true,
         "message" => "Request to be a Creditor Received Successfully",
      ], 200);
   }
   return response()->json([
      "success" => false,
      "message" => "Customer Not found"
   ], 409);
}
public function creditorStatus(Request $request){
   $customer = customers::where('id', $request->customer_id)->first();
   if ($customer){
      return response()->json([
         "success" => true,
         "message" => "Customer Status",
         "data"=>$customer->creditor_status,
      ], 200);
   }
   return response()->json([
      "success" => false,
      "message" => "Customer Not found"
   ], 409);
}
public function groups(){
      return response()->json([
      "success" => false,
      "message" => "Customer Groups",
      "data" => customer_groups::all(),
   ], 200);
}

   public function updateCustomerProfile(Request $request){

      $validator = Validator::make($request->all(), [
         'customer_name' => 'required',
         'email' => 'nullable',
         'address' => 'nullable',
         'contact_person' => 'nullable',
         'phone_number' => 'nullable',
         'telephone' => 'nullable'

      ]);
      if ($validator->fails()) {
         return response()->json(['errors' => $validator->errors()], 422);

      }

      $user_code=$request->user()->user_code;
      // $image_path = $request->file('image')->store('image', 'public');

      $name=$request->user()->name;
      $customers=customers::where('user_code', $user_code)->first();


      $customer_data = [
         'email' => $request->email,
         'address' => $request->address,
         'customer_name' => $request->customer_name,
         'contact_person' => $request->contact_person,
         'telephone' => $request->telephone,
         'phone_number' => $request->phone_number,

      ];

      // Remove null or empty values
      $customer_data = array_filter($customer_data, function($value) {
         return !is_null($value) && $value !== '';
      });

      // Update the customer with the filtered data
      $customer = $customers->update($customer_data);

      $user=User::where('user_code', $user_code)->update(

         [

            'name' => $request->customer_name,
            'email' => $request->email,
            'location' => $request->address,
            'phone_number' => $request->phone_number,

         ]
      );
      return response()->json([
         "success" => true,
         "message" => "Updated customer profile",
         "customer" => $customers
      ]);



   }
   public function updateCustomerImage(Request $request){
      $validator = Validator::make($request->all(), [
         'image' => 'required',

      ]);
      if ($validator->fails()) {
         return response()->json(['errors' => $validator->errors()], 422);

      }

      $user_code=$request->user()->user_code;
      $image_path = $request->file('image')->store('image', 'public');

      $name=$request->user()->name;
      $customers=customers::where('user_code', $user_code)->first();


      $customer_data = [
         'image' => $image_path,
      ];

      // Remove null or empty values
      $customer_data = array_filter($customer_data, function($value) {
         return !is_null($value) && $value !== '';
      });

      // Update the customer image with the filtered data
      $customer = $customers->update($customer_data);

      return response()->json([
         "success" => true,
         "message" => "Updated customer image",
         "image" => $image_path
      ]);

   }
   public function add_customer(Request $request)
   {
      $validator = Validator::make($request->all(), [
         "customer_name"   => "required|unique:customers",
         "contact_person"  => "required",
         "business_code"   => "required",
         "phone_number"    => "required|unique:customers",
         "email"           => "nullable|unique:users",
         "latitude"        => "required",
         "longitude"       => "required",
         "image"           => 'required|image|mimes:jpg,png,jpeg,gif,svg',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "status" => 401,
            "message" => "validation_error",
            "errors" => $validator->errors()
         ], 403);
      }

      $image_path = $request->file('image')->store('image', 'public');
      $emailData = $request->email ?? $request->customer_name . Str::random(3) . '@gmail.com';
      $route = Routes::with('subregion.region')->find($request->route_code);
      $user = User::create([
         'name' => $request->customer_name,
         'email' => $emailData,
         'user_code' => Str::random(20),
         'phone_number' => $request->phone_number,
         'gender' => $request->gender,
         'account_type' => "Customer",
         'email_verified_at' => Carbon::now(),
         'status' => "Active",
         'region_id' => optional($route->subregion->region)->id,
         'business_code' =>$request->business_code,
         'password' => Hash::make("password")
      ]);
     customers::create([
         'customer_name' => $request->customer_name,
         'contact_person' => $request->contact_person,
         'phone_number' => $request->phone_number,
         'user_code' => $user->user_code,
         'email' => $emailData,
         'address' => $request->address,
         'latitude' => $request->latitude,
         'longitude' => $request->longitude,
         'business_code' => $request->business_code,
         'created_by' => $request->user()->user_code,
         'route_code' => $request->route_code,
         'route' => $request->route_code,
         'customer_group' => $request->outlet,
         'price_group' => $request->outlet,
         'region_id' => optional($route->subregion->region)->id,
         'subregion_id' => optional($route->subregion)->id,
         'unit_id' => $request->route_code,
         'image' => $image_path
      ]);

      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Customer added successfully",
      ]);
   }

   public function add_customer2(Request $request)
   {
      //   $user_code = $request->user()->user_code;
      $validator           =  Validator::make($request->all(), [
         "customer_name"   => "required|unique:customers",
         "contact_person"  => "required",
         "business_code"   => "required",
         "phone_number"    => "required|unique:customers",
         "email"           => "nullable|unique:users",
         "latitude"        => "required",
         "longitude"       => "required",
         "image" => 'required|image|mimes:jpg,png,jpeg,gif,svg',
      ]);

      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401,
               "message" =>
               "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }

      $random=Str::random(3);
      $image_path = $request->file('image')->store('image', 'public');
      $emailData = $request->email !== null ? $request->email : $request->customer_name.$random.'@gmail.com';
      $random=Str::random(20);
      $route=Routes::where('id', $request->route_code)->first();
      $subregion=Subregion::where('id', $route->subregion_id)->first();
      $region=Region::where('id', $subregion->region_id)->first();
      $user = new User();
      $user->name = $request->customer_name;
      $user->email=$emailData;
      $user->user_code=$random;
      $user->phone_number = $request->phone_number;
      $user->gender = $request->gender;
      $user->account_type= "Customer";
      $user->email_verified_at =Carbon::now();
      $user->status="Active";
      $user->region_id= $region->id ?? Auth::user()->region_id;
      $user->business_code = Auth::user()->business_code;
      $user->password = Hash::make("password");
      $user->save();

      $customer = new customers;
      $customer->customer_name = $request->customer_name;
      $customer->contact_person = $request->contact_person;
      $customer->phone_number = $request->phone_number;
      $customer->user_code = $user->user_code;
      $customer->email = $emailData;
      $customer->address = $request->address;
      $customer->latitude = $request->latitude;
      $customer->longitude = $request->longitude;
      $customer->business_code = $request->business_code;
      $customer->created_by = $request->user()->user_code;
      $customer->route_code = $request->route_code;
      $customer->route = $request->route_code;
      $customer->customer_group = $request->outlet;
      $customer->price_group = $request->outlet;
      $customer->region_id = $region->id;
      $customer->subregion_id = $subregion->id;
      $customer->unit_id = $request->route_code;
      $customer->image = $image_path;
      $customer->save();

      DB::table('leads_targets')
         ->where('user_code', $request->user()->user_code)
         ->increment('AchievedLeadsTarget');

      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Customer added successfully",
      ]);
   }

   public function editCustomer(Request $request)
   {
      $customer = customers::whereId($request->id)->first();

      $edited = customers::whereId($request->id)->update(
         [
            "customer_name" => $request->customer_name ?? $customer->customer_name,
            "account" => $request->account ?? $customer->account,
            "address" => $request->address ?? $customer->address,
            "latitude" => $request->latitude ?? $customer->latitude,
            "longitude" => $request->longitude ?? $customer->longitude,
            "contact_person" => $request->contact_person ?? $customer->contact_person,
            "customer_group" => $request->customer_group ?? $customer->customer_group,
            "price_group" => $request->price_group ?? $customer->price_group,
            "route" => $request->route ?? $customer->route,
            "region_id" => $request->route ?? $customer->route,
            "unit_id" => $request->route ?? $customer->route,
            "approval" => 'Approved' ?? $customer->approval,
            "status" => 'Active' ?? $customer->status,
            "telephone" => $request->telephone ?? $customer->telephone,
            "manufacturer_number" => $request->manufacturer_number ?? $customer->manufacturer_number,
            "vat_number" => $request->vat_number ?? $customer->vat_number,
            "delivery_time" => $request->delivery_time ?? $customer->delivery_time,
            "city" => $request->city ?? $customer->city,
            "province" => $request->province ?? $customer->province,
            "postal_code" => $request->postal_code ?? $customer->postal_code,
            "country" => $request->country ?? $customer->country,
            "customer_secondary_group" => $request->customer_secondary_group ?? $customer->customer_secondary_group,
            "branch" => $request->branch ?? $customer->branch,
            "email" => $request->email ?? $customer->email,
            "phone_number" => $request->phone_number ?? $customer->phone_number,
            "business_code" => $request->user()->business_code ?? $customer->business_code,
            "created_by" => $request->user()->id ?? $customer->id
         ]
      );

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Editing customer information';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Customer information update ';
      $activityLog->action = 'User '.auth()->user()->name.' updated customer '. $customer->customer_name.' information';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();


      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Customer editted successfully",
         "customer" => $edited
      ]);
   }
   public function calculate_distance(Request $request)
   {
      $id = $request->user()->id;
      $customer = customers::where('account', $request->customer)->first();
      $lat1 = $customer->latitude;
      $lon1 = $customer->longitude;
      $lat2 = $request->latitude;
      $lon2 = $request->longitude;
      $unit = "K";

      $distance = round(Helper::distance($lat1, $lon1, $lat2, $lon2, $unit), 2);

      // if($distance < 0.05){

      //create a check in session
      $checkin = new checkin();
      $checkin->code = Helper::generateRandomString(20);
      $checkin->customer_id =  $customer->id;
      $checkin->account_number = $request->customer;
      $checkin->checkin_type = $this->checkVisit($id, $customer->id);
      $checkin->user_code = Auth::user()->user_code;
      $checkin->ip = Helper::get_client_ip();
      $checkin->start_time = date('H:i:s');
      $checkin->business_code = Auth::user()->business_code;
      $checkin->save();

      //recorord activity
      $activities = '<b>' . Auth::user()->name . '</b> Has <b>Checked-in</b> to <i> ' . $customer->customer_name . '</i> @ ' . date('H:i:s');
      $section = 'Customer';
      $action = 'Checkin';
      $business_code = Auth::user()->business_code;
      $activityID = $checkin->code;

      Helper::activity($activities, $section, $action, $activityID, $business_code);

      return redirect()->route('customer.checkin', $checkin->code);

      // }else{
      //    Session::flash('warning','You are not near the customer shop');
      //    return redirect()->back();
      // }

   }

   public function checkVisit($user_id, $customer_id)
   {

      $today = Carbon::today()->format('Y-m-d');
      $visit = null;
      $checker = Routes::with([
         'RouteSales' => function ($query) use ($user_id) {
            $query->where('userID', $user_id);
         }
      ])
         ->where('start_date', '>', $today)
         ->where('end_date', '<', $today)
         ->pluck('route_code');
      if ($checker !== null) {
         $route_customer = Route_customer::whereIn('routeID', $checker)->where('customer_id', $customer_id)->get();
         if ($route_customer !== null) {
            $visit = "Admin";
         }
      }
      return $visit;
   }
   /**
    * Customer deliveries
    *
    * @param string $customerID this is the customer ID
    * @param string $business_code this is the Business code
    **/
   public function deliveries($customerID, $business_code)
   {
      $delivery = Delivery::where('customer', $customerID)->where('business_code', $business_code)->orderby('id', 'desc')->get();

      return response()->json([
         "success"  => true,
         "status"   => 200,
         "message"  => "Customer Deliveries",
         "data" => $delivery,
      ]);
   }

   /**
    * Customer deliveries
    *
    * @param string $code this is the delivery unique code
    **/
   public function delivery_details($code)
   {
      $delivery = Delivery::where('delivery_code', $code)->first();

      //      $products = Delivery_items::join('product_information','product_information.id','=','delivery_items.productID')
      //                               ->where('delivery_code',$code)
      //                             ->get();


      $products = Delivery_items::join('product_price', 'product_price.productID', '=', 'delivery_items.productID')
         ->select('*', DB::raw('(selling_price * allocated_quantity) as total_amount'))
         ->where('delivery_code', $code)
         ->get();
      return response()->json([
         "success"  => true,
         "status"   => 200,
         "message"  => "Customer added successfully",
         "delivery" => $delivery,
         "products" => $products,
      ]);
   }

   /**
    * Customer Orders
    *
    * @param string $customerID this is the customer ID
    **/
   public function orders($customerID)
   {
      $orders = Orders::where('customerID', $customerID)->orderby('orders.id', 'desc')->get();

      return response()->json([
         "success"  => true,
         "status"   => 200,
         "message"  => "Customer orders",
         "orders" => $orders,
      ]);
   }

   /**
    * Order details
    *
    * @param string $orderCode this is the order code
    **/
   public function order_details($orderCode)
   {
      $order = Orders::where('order_code', $orderCode)->first();
      $items =  Order_items::where('order_code', $orderCode)->get();
      $orders =  Cart::where('order_code', $orderCode)->get();
      $payment = order_payments::where('order_id', $orderCode)->get();
      return response()->json([
         "success"  => true,
         "status"   => 200,
         "message"  => "Customer orders",
         "order_items" => $orders,
         "items" => $items,
         "Data" => $order,
         "Payment" => $payment
      ]);
   }

   /**
    * New orders
    *
    * @param string $customerID this is the customer ID
    **/
   public function new_order($customerID)
   {
      $orders = Orders::where('customerID', $customerID)
         ->where('order_status', 'Pending Delivery')
         ->orderby('orders.id', 'desc')->get();

      return response()->json([
         "success"  => true,
         "status"   => 200,
         "message"  => "Pending Delivery",
         "Data" => $orders,
      ]);
   }
}
