<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\Area;
use App\Models\Role;
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
      $distributors = suppliers::whereNotIn('name', ['Sidai', 'sidai'])->orWhereNull('name')->orWhere('name', '')
         ->whereIn('status', ['Active', 'active'])
         ->orWhereNull('status')
         ->orWhere('status', '')
         ->get();

      return response()->json(['users' => $distributors]);
   }
   //list
   public function list()
   {
      $accountTypes = Role::pluck('name')->toArray();

      $lists = User::whereIn('account_type', $accountTypes)
         ->distinct('account_type')
         ->whereNotIn('account_type', ['Customer'])
         ->groupBy('account_type')
         ->pluck('account_type');
      $counts = User::join('roles', 'users.account_type', '=', 'roles.name')
         ->whereIn('users.account_type', $accountTypes)
         ->whereNotIn('users.account_type', ['Customer'])
         ->groupBy('users.account_type')
         ->selectRaw('users.account_type, count(*) as count')
         ->pluck('count', 'users.account_type');

      $count = 1;
      return view('app.users.list', compact('lists', 'counts','count'));
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
   //store
   public function store(Request $request)
   {
      $this->validate($request, [
         'email' => 'required|email|unique:users',
         'name' => 'required',
         'phone_number' => 'required|unique:users',
         'account_type' => 'required',
         'region' => 'required',
      ]);
      $user_code = Str::random(20);
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
      Session()->flash('success', 'User Created Successfully, Default Password is Phone_number');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Adding User';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Creating User';
      $activityLog->action = 'User ' . $request->name . ' Role ' . $request->account_type . ' Created Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = "";
      $activityLog->save();

      if ($request->account_type ==='RSM')
      {
         $rsm = User::where('account_type', 'RSM');
         return view('app.users.rsm', compact('rsm'));
      }
      elseif ($request->account_type === 'NSM'){
         $nsm = User::where('account_type', 'NSM');
         return view('app.users.rsm', compact('nsm'));
      }
      elseif ($request->account_type ==='Shop-Attendee'){
         $shopattendee = User::where('account_type', 'Shop-Attendee');
         return view('app.users.shopattendee', compact('shopattendee'));

      }elseif ($request->account_type === 'TD'){
         $td = User::where('account_type', 'td');
         return view('app.users.rsm', compact('td'));

      }elseif ($request->account_type === 'TSR'){
         $tsr = User::where('account_type', 'TSR');
         return view('app.users.rsm', compact('tsr'));
      }else

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
      $activityLog->action = 'User ' . $request->name . ' updated';
      $activityLog->activityID = $random;
      $activityLog->ip_address = "";
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
