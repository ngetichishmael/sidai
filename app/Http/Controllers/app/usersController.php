<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\AppPermission;
use App\Models\Area;
use App\Models\laratrust\Role_user;
use App\Models\Region;
use App\Models\Role;
use App\Models\Subregion;
use App\Models\suppliers\suppliers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
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
      //$roles = Role::withCount('users')->pluck('name')->toArray();

//      $lists = User::whereIn('account_type', $accountTypes)
//         ->distinct('account_type')
//         ->whereNotIn('account_type', ['Customer'])
//         ->groupBy('account_type')
//         ->pluck('account_type');
//      $counts = User::join('roles', 'users.account_type', '=', 'roles.name')
//         ->whereIn('users.account_type', $accountTypes)
//         ->whereNotIn('users.account_type', ['Customer'])
//         ->groupBy('users.account_type')
//         ->selectRaw('users.account_type, count(*) as count')
//         ->pluck('count', 'users.account_type');

//      $count = 1;
      $roles = Role::withCount('users')->get();
      return view('app.users.list', compact( 'roles'));
   }
   public function viewRole($role)
   {
      $users = User::where('account_type',$role);
      $description=Role::where('name', $role)->first();
      return view('app.users.index', compact('users', 'description', 'role'));
   }
//   public function nsm()
//   {
//      $admin = User::where('account_type', 'NSM');
//      return view('app.users.index', compact('admin'));
//   }
//   public function shopattendee()
//   {
//      $shopattendee = User::where('account_type', 'Shop-Attendee');
//      return view('app.users.shopattendee', compact('shopattendee'));
//   }
//   public function tsr()
//   {
//      $tsr = User::where('account_type', 'TSR');
//      return view('app.users.tsr', compact('tsr'));
//   }
//   public function td()
//   {
//      $td = User::where('account_type', 'TD');
//      return view('app.users.td', compact('td'));
//   }
//   public function rsm()
//   {
//      $rsm = User::where('account_type', 'RSM');
//      return view('app.users.rsm', compact('rsm'));
//   }
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
      $subregions = Subregion::all();
      $routes = Area::all();
      $roles= Role::all();
      return view('app.users.create', [
         "routes" => $routes,
         "regions" => $regions,
         "subregions" => $subregions,
         "roles"=>$roles

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
      $user=User::updateOrCreate(
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
      $role=Role::where('name', $request->account_type)->first();
      if ($role){
         $user->roles()->sync($role->id);
      }
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
//      return redirect()->back();
      return redirect()->to(url()->previous());
   }

   //edit
   public function edit($user_code)
   {
      $edit = User::where('user_code', $user_code)
         ->where('business_code', FacadesAuth::user()->business_code)
         ->first();
      $permissions = AppPermission::where('user_code', $user_code)->firstOrFail();

      $regions = Region::all();
      $user_region=Region::where('id', $edit->region_id)->first();
      $roles= Role::all();
      return view('app.users.edit', [
         'edit' => $edit,
         'user_code' => $user_code,
         'permissions' => $permissions,
         'regions' => $regions,
         'roles' =>$roles,
         'user_region'=>$user_region
      ]);
   }
   public function show($user_code)
   {
      $edit = User::where('user_code', $user_code)
         ->where('business_code', FacadesAuth::user()->business_code)
         ->first();
      $permissions = AppPermission::where('user_code', $user_code)->firstOrFail();
      $user_role=Role_user::where('user_id', $edit->id)->first();
      $role_detail=[];
         if($user_role){
            $role_detail=Role::where('id', $user_role->role_id)->with('permissions')->get();
         }
      $regions = Region::all();
      $roles= Role::all();
      return view('app.users.view', [
         'user' => $edit,
         'user_code' => $user_code,
         'permissions' => $permissions,
         'regions' => $regions,
         'roles' =>$roles,
         'role_detail'=>$role_detail
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

      $user=User::updateOrCreate(
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
      $role=Role::where('name', $request->account_type)->first();
      if ($role){
         $user->roles()->sync($role->id);
      }
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

//      return redirect()->back();

      $role=$request->initial_role;
      $users = User::where('account_type',$role);
      $description=Role::where('name', $role)->first();
      if (!empty($description)){
         return view('app.users.index', compact('users', 'description', 'role'));
      }
       return redirect()->to(url()->previous());
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
