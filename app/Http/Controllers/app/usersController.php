<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\Area;
use App\Models\suppliers\suppliers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AppPermission;
use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;

class usersController extends Controller
{
   public function getUsers(Request $request)
   {
      $accountType = $request->input('account_type');
      $users = User::where('account_type', $accountType)->get();

      return response()->json(['users' => $users]);
   }
   public function getDistributors(Request $request)
   {
      $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
         ->orWhereNull('status')
         ->orWhere('status', '')
         ->get();

      return response()->json(['users' => $distributors]);
   }
   //list
   public function list()
   {
      $lists = User::whereIn('account_type',['NSM','RSM','TD','TSR', 'Shop-Attendee'])
         ->distinct('account_type')
         ->whereNotIn('account_type', ['Customer'])
         ->groupBy('account_type')
         ->pluck('account_type');
      $count = 1;
      return view('app.users.list', compact('lists','count'));
   }
   public function nsm()
   {
      $admin = User::where('account_type', 'NSM');
      return view('app.users.index', compact('admin'));
   }
   public function shopattendee()
   {
      $shopattendee = User::where('account_type', 'Shop-Attendee');
      return view('app.users.shopattendee', compact('shopattendee'));
   }
   public function tsr()
   {
      $tsr = User::where('account_type', 'TSR');
      return view('app.users.tsr', compact('tsr'));
   }
   public function td()
   {
      $td = User::where('account_type', 'TD');
      return view('app.users.td', compact('td'));
   }
   public function rsm()
   {
      $rsm = User::where('account_type', 'RSM');
      return view('app.users.rsm', compact('rsm'));
   }
   public function index()
   {
      return view('app.users.index');
   }
   public function indexUser()
   {
      return view('app.users.import');
   }

   public function reports()
   {
      $reports = [
            "Preorder Report",
            "Vansale Report",
            "Delivery Report",
            "Sidai Users Report",
            "Customers Report",
            "Warehouse Report",
            "Distributors Report",
            "Regional Report",
            "Suppliers Report",
            "Payments Report",
            "Inventory Report",
            "Visitation Reports",
            "Target Reports"
      ];
      $count = 1;
      return view('app.users.reports', compact('reports','count'));
   }

   //create
   public function create()
   {
      // $routes = array_merge($regions, $subregions, $zones);
      $regions = Region::all();
      $routes = Area::all();
      return view('app.users.create', [
         "routes" => $routes,
         "regions" => $regions
      ]);
   }
   public function creatensm()
   {
      // $routes = array_merge($regions, $subregions, $zones);
      $regions = Region::all();
      $routes = Area::all();
      return view('app.users.creatensm', [
         "routes" => $routes,
         "regions" => $regions
      ]);
   }
   public function sendOTP($number, $code)
   {

      try {
         $curl = curl_init();

         curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://prsp.jambopay.co.ke/api/api/org/disburseSingleSms/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "number" : "' . $number . '",
                "sms" : ' . $code . ',
                "callBack" : "https://....",
                "senderName" : "PASANDA"
          }
          ',
            CURLOPT_HTTPHEADER => array(
               'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXlsb2FkIjp7ImlkIjozNywibmFtZSI6IkRldmVpbnQgTHRkIiwiZW1haWwiOiJpbmZvQGRldmVpbnQuY29tIiwibG9jYXRpb24iOiIyMyBPbGVuZ3VydW9uZSBBdmVudWUsIExhdmluZ3RvbiIsInBob25lIjoiMjU0NzQ4NDI0NzU3IiwiY291bnRyeSI6IktlbnlhIiwiY2l0eSI6Ik5haXJvYmkiLCJhZGRyZXNzIjoiMjMgT2xlbmd1cnVvbmUgQXZlbnVlIiwiaXNfdmVyaWZpZWQiOmZhbHNlLCJpc19hY3RpdmUiOmZhbHNlLCJjcmVhdGVkQXQiOiIyMDIxLTExLTIzVDEyOjQ5OjU2LjAwMFoiLCJ1cGRhdGVkQXQiOiIyMDIxLTExLTIzVDEyOjQ5OjU2LjAwMFoifSwiaWF0IjoxNjQ5MzEwNzcxfQ.4y5XYFbC5la28h0HfU6FYFP5a_6s0KFIf3nhr3CFT2I',
               'Content-Type: application/json'
            ),
         ));

         $response = curl_exec($curl);

         curl_close($curl);
      } catch (Exception $e) {
      }
   }

   //store
   public function store(Request $request)
   {
      $this->validate($request, [
         'email' => 'required',
         'name' => 'required',
         'phone_number' => 'required',
         'account_type' => 'required',
         'region' => 'required',
      ]);
      $user_code = rand(100000, 999999);
      //save user
      $code = rand(100000, 999999);
      User::updateOrCreate(
         [
            "user_code" => $user_code,

         ],
         [
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "name" => $request->name,
            "account_type" => $request->account_type,
            "email_verified_at" => now(),
            "route_code" => $request->region,
            "region_id" => $request->region,
            "status" => 'Active',
            "password" => Hash::make($request->phone_number),
            "business_code" => FacadesAuth::user()->business_code,

         ]
      );
      $van_sales = $request->van_sales == null ? "NO" : "YES";
      $new_sales = $request->new_sales == null ? "NO" : "YES";
      $deliveries = $request->deliveries == null ? "NO" : "YES";
      $schedule_visits = $request->schedule_visits == null ? "NO" : "YES";
      $merchanizing = $request->merchanizing == null ? "NO" : "YES";
      AppPermission::updateOrCreate(
         [
            "user_code" => $user_code,

         ],
         [
            "van_sales" => $van_sales,
            "new_sales" => $new_sales,
            "schedule_visits" => $schedule_visits,
            "deliveries" => $deliveries,
            "merchanizing" => $merchanizing,
         ]
      );
//      try {
//         $curl = curl_init();
//
//         curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://prsp.jambopay.co.ke/api/api/org/disburseSingleSms/',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'POST',
//            CURLOPT_POSTFIELDS => '{
//               "number" :  "' . $request->phone_number . '",
//               "sms" : ' . $code . ',
//               "callBack" : "https://....",
//               "senderName" : "PASANDA"
//         }
//         ',
//            CURLOPT_HTTPHEADER => array(
//               'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXlsb2FkIjp7ImlkIjozNywibmFtZSI6IkRldmVpbnQgTHRkIiwiZW1haWwiOiJpbmZvQGRldmVpbnQuY29tIiwibG9jYXRpb24iOiIyMyBPbGVuZ3VydW9uZSBBdmVudWUsIExhdmluZ3RvbiIsInBob25lIjoiMjU0NzQ4NDI0NzU3IiwiY291bnRyeSI6IktlbnlhIiwiY2l0eSI6Ik5haXJvYmkiLCJhZGRyZXNzIjoiMjMgT2xlbmd1cnVvbmUgQXZlbnVlIiwiaXNfdmVyaWZpZWQiOmZhbHNlLCJpc19hY3RpdmUiOmZhbHNlLCJjcmVhdGVkQXQiOiIyMDIxLTExLTIzVDEyOjQ5OjU2LjAwMFoiLCJ1cGRhdGVkQXQiOiIyMDIxLTExLTIzVDEyOjQ5OjU2LjAwMFoifSwiaWF0IjoxNjQ5MzEwNzcxfQ.4y5XYFbC5la28h0HfU6FYFP5a_6s0KFIf3nhr3CFT2I',
//               'Content-Type: application/json'
//            ),
//         ));
//
//         $response = curl_exec($curl);
//
//         curl_close($curl);
//      } catch (Exception $e) {
//      }

      Session()->flash('success', 'User Created Successfully, Default Password is Phone_number');
      // Redirect::back()->with('message', 'User Created Successfully');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Adding User';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Creating User';
      $activityLog->action = 'User '. $request->name. ' Role '.$request->account_type.' Created Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->back();
   }

   //edit
   public function edit($user_code)
   {
      $edit = User::where('user_code', $user_code)
         ->where('business_code', FacadesAuth::user()->business_code)
         ->first();
      $permissions = AppPermission::where('user_code', $user_code)->firstOrFail();

      $regions = Region::all();

      return view('app.users.edit', [
         'edit' => $edit,
         'user_code' => $user_code,
         'permissions' => $permissions,
         'regions' => $regions,
      ]);
   }

   //update
   public function update(Request $request, $user_code)
   {
      $this->validate($request, [
         'email' => 'required',
         'name' => 'required',
         'phone_number' => 'required',
         'account_type' => 'required',
      ]);

      User::updateOrCreate(
         [
            "user_code" => $user_code,
            "business_code" => FacadesAuth::user()->business_code,
         ],
         [
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "name" => $request->name,
            "account_type" => $request->account_type,
            "status" => 'Active',
            "region_id" => $request->region,

         ]
      );
      $van_sales = $request->van_sales == null ? "NO" : "YES";
      $new_sales = $request->new_sales == null ? "NO" : "YES";
      $deliveries = $request->deliveries == null ? "NO" : "YES";
      $schedule_visits = $request->schedule_visits == null ? "NO" : "YES";
      $merchanizing = $request->merchanizing == null ? "NO" : "YES";
      AppPermission::updateOrCreate(
         [
            "user_code" => $user_code,
         ],
         [
            "van_sales" => $van_sales,
            "new_sales" => $new_sales,
            "schedule_visits" => $schedule_visits,
            "deliveries" => $deliveries,
            "merchanizing" => $merchanizing,
         ]
      );

      Session()->flash('success', 'User updated Successfully');

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'User update';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'User update';
      $activityLog->action = 'User '.$request->name.' updated';
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->back();
   }
//   public function destroy($id)
//   {
//      User::where('id', $id)->delete();
//      Session()->flash('success', 'User deleted Successfully');
//      return redirect()->route('users.index');
//   }
   public function import()
   {
      abort(403, "This action is Limited to Admin Only");
   }
}
