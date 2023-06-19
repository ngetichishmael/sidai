<?php

namespace App\Http\Controllers\app\customer;

use App\Helpers\Helper;
use App\Models\activity_log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\customer_group;
use App\Models\price_group;
use App\Models\customer\customers;
use App\Models\country;
use App\Models\customer\groups;
use App\Models\PriceGroup;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\suppliers\supplier_address;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class customerController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }

   public function index()
   {
      return view('app.customers.index');
   }
   public function customergroups()
   {
      return view('livewire.customer-group.customergroup');
   }

   public function creditor()
   {
      return view('app.creditors.index');
   }
   public function approveCreditors()
   {
      return view('app.creditors.approve');
   }
   public function show()
   {
      return view('app.customers.index');
   }
   public function groupstore(Request $request)
   {
      $customer = new customer_group;
      $customer->group_name = $request->group_name;
      $customer->business_code = $request->business_code;
      $customer->save();

      Session::flash('success', 'Customer Group Added');
      return redirect()->route('groupings');

   }

   public function create()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $groups = customer_group::all();
      $pricing = PriceGroup::get();
      $prices = price_group::all();

      return view('app.customers.create', compact('country', 'groups', 'pricing','prices'));
   }
   public function createcreditor()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $groups = customer_group::all();
      $pricing = PriceGroup::get();
      $prices = price_group::all();
      return view('app.customers.creditor', compact('country', 'groups', 'pricing','prices'));
   }

   public function details($id)
   {
      $customer = Customers::find($id);
      return view('app.customers.show', ['customer' => $customer]);
   }
   public function creditordetails($id)
   {
      $customer = customers::find($id);
      return view('app.creditors.show', ['customer' => $customer]);
   }

   public function approvecreditor($id)
   {
      $c=Customers::whereId($id)->update(
         [
            'customer_type' => 'creditor',
            'creditor_approved' => '1'
         ]
      );
      Session::flash('success', 'Customer successfully Approved');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Customer approved';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Customer successfully Approved';
      $activityLog->action = 'Customer With id'. $c.' successfully Approved';
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->route('creditors');
   }

   public function store(Request $request)
   {
      $this->validate($request, [
         'customer_name' => 'required'
      ]);
      $emailData = $request->email == null ? null : $request->email;
      $random=Str::random(10);
      $user = new User();
      $user->name = $request->customer_name;
      $user->email=$emailData;
      $user->user_code=$random;
      $user->phone_number = $request->phone_number;
      $user->gender = $request->gender;
      $user->account_type= "Customer";
      $user->email_verified_at =Carbon::now();
      $user->status="Active";
      $user->region_id= $request->region;
      $user->business_code = Auth::user()->business_code;
      $user->password = Hash::make("password");
      $user->save();


      $customer = new customers;
      $customer->customer_name = $request->customer_name;
      $customer->user_code = $user->user_code;
      $customer->id_number = $user->id_number;
      $customer->contact_person = $request->contact_person;
      $customer->telephone = $request->telephone;
      $customer->address = $request->address;
      $customer->price_group = $request->price_group;
      $customer->customer_group = $request->customer_group;
      $customer->route = $request->route;
      $customer->route_code = $request->route;
      $customer->zone_id = $request->route;
      $customer->status = "Active";
      $customer->email = $request->email;
      $customer->customer_type = "normal";
      $customer->phone_number = $request->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();

      Session::flash('success', 'Customer successfully Added');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Creating customer';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Add customer';
      $activityLog->action = 'Customer ' . $customer->customer_name . ' successfully Created';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = "";
      $activityLog->save();

      return redirect()->route('customer');
   }
   public function storecreditor(Request $request)
   {
      $this->validate($request, [
         'customer_name' => 'required',
         'id_number' => 'required',
      ]);

      $customer = new customers;
      $customer->customer_name = $request->customer_name;
      $customer->id_number =$request->id_number;
      $customer->address = $request->address;
      $customer->contact_person = $request->contact_person;
      $customer->telephone = $request->telephone;
      $customer->price_group = $request->pricing_category;
      $customer->customer_group = $request->customer_group;
      $customer->route = $request->route;
      $customer->route_code = $request->territory;
      $customer->zone_id = $request->territory;
      $customer->email = $request->email;
      $customer->customer_type = "creditor";
      $customer->creditor_approved = 1;
      $customer->phone_number = $request->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();

      $emailData = $request->email == null ? null : $request->email;
      $random=Str::random(10);
      $user = new User();
      $user->name = $request->customer_name;
      $user->email=$request->customer_name;
      $user->user_code=$random;
      $user->phone_number = $request->phone_number;
      $user->gender = $request->gender;
      $user->account_type= "Customer";
      $user->email_verified_at =Carbon::now();
      $user->status="Active";
      $user->region=Auth::user()->region_id;
      $user->business_code = Auth::user()->business_code;
      $user->password = "password";
      $user->save();

      Session::flash('success', 'Customer successfully Creditor Added');

      return redirect()->route('creditors');
   }

   public function edit($id)
   {

      $regions = Region::all();
      $subregions = Subregion::all();
      $areas = Area::all();
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $customer = customers::where('customers.id', $id)
         ->select('*', 'customers.id as customerID')
         ->first();
      $subregion_id = Area::whereId($customer->route ?? $customer->route_code)->pluck('subregion_id')->implode('');
      $region_id = Subregion::whereId($subregion_id)->pluck('region_id')->implode('');
      $customer->update([
         'subregion_id' => $subregion_id,
         'region_id' => $region_id,
      ]);
      $groups = groups::get();
      $pricing = PriceGroup::get();
      return view('app.customers.edit',
         compact('customer', 'country', 'regions', 'subregions', 'areas', 'groups', 'pricing')
      );
   }
   public function editcreditor($id)
   {
      $regions = Region::all();
      $subregions = Subregion::all();
      $areas = Area::all();
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $customer = customers::where('customers.id', $id)
         ->select('*', 'customers.id as customerID')
         ->first();
      $subregion_id = Area::whereId($customer->route ?? $customer->route_code)->pluck('subregion_id')->implode('');
      $region_id = Subregion::whereId($subregion_id)->pluck('region_id')->implode('');
      $customer->update([
         'subregion_id' => $subregion_id,
         'region_id' => $region_id,
      ]);
      $groups = customer_group::all();
      $prices = price_group::all();
      $pricing = PriceGroup::get();
      return view('app.creditors.edit',
         compact('customer', 'country', 'regions', 'subregions', 'areas', 'groups', 'pricing','prices')
      );
   }

   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'customer_name' => 'required'
      ]);
$region=
      $customer = customers::where('id', $id)->first();
      $customer->customer_name = $request->customer_name ?? $customer->customer_name;
      $customer->id_number = $request->id_number ?? $customer->id_number;
      $customer->contact_person = $request->contact_person;
      $customer->telephone = $request->telephone;
      $customer->address = $request->address ?? $customer->address;
      $customer->price_group = $request->pricing_category ?? $customer->pricing_category;
      $customer->customer_secondary_group = $request->customer_secondary_group ?? $customer->customer_secondary_group;
      $customer->route = $request->route ?? $customer->route;
      $customer->route_code = $request->territory ?? $customer->territory;
      $customer->zone_id = $request->territory ?? $customer->territory;
      $customer->branch = $request->branch ?? $customer->branch;
      $customer->email = $request->email ?? $customer->email;
      $customer->phone_number = $request->phone_number ?? $customer->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();

      $user=User::where('user_code', $customer->user_code)->first();
      if ($user != null || !empty($user)) {
         $user->region_id = $request->region ?? Auth::user()->region_id ?? null;
         $user->save();
      }
      Session::flash('success', 'Customer updated successfully');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Updating customer details';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Update customer';
      $activityLog->action = 'Customer ' . $customer->customer_name . ' successfully ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = "";
      $activityLog->save();

      return redirect()->route('customer');
   }
   public function updatecreditor(Request $request, $id)
   {
      $this->validate($request, [
         'customer_name' => 'required'
      ]);

      $customer = customers::where('id', $id)->first();
      $customer->customer_name = $request->customer_name ?? $customer->customer_name;
      $customer->contact_person = $request->contact_person;
      $customer->telephone = $request->telephone;
      $customer->price_group = $request->pricing_category ?? $customer->pricing_category;
      $customer->customer_group = $request->customer_group ?? $customer->customer_group;
      $customer->customer_secondary_group = $request->customer_secondary_group ?? $customer->customer_secondary_group;
      $customer->route = $request->route ?? $customer->route;
      $customer->route_code = $request->territory ?? $customer->route;
      $customer->zone_id = $request->territory ?? $customer->territory;
      $customer->branch = $request->branch ?? $customer->branch;
      $customer->email = $request->email ?? $customer->email;
      $customer->phone_number = $request->phone_number ?? $customer->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();


      Session::flash('success', 'Creditor updated successfully');

      return redirect()->route('creditors');
   }


   public function delete($id)
   {
   }


   public function express_store(Request $request)
   {
      $primary = new customers;
      $primary->customer_name = $request->customer_name;
      $primary->email = $request->email;
      $primary->primary_phone_number = $request->phone_number;
      $primary->businessID = FacadesAuth::user()->business_code;
      $primary->created_by = FacadesAuth::user()->id;
      $primary->save();

      $address = new supplier_address();
      $address->customerID = $primary->id;
      $address->save();
   }

   public function express_list()
   {
      $accounts = customers::where('businessID', FacadesAuth::user()->business_code)->orderby('id', 'desc')->get(['id', 'customer_name as text']);
      return ['results' => $accounts];
   }

}
