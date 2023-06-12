<?php

namespace App\Http\Controllers\app\customer;

use App\Helpers\Helper;
use App\Models\activity_log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
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

   public function creditor()
   {
      return view('app.creditors.index');
   }
   public function show()
   {
      return view('app.customers.index');
   }

   public function create()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $groups = groups::get();
      $pricing = PriceGroup::get();

      return view('app.customers.create', compact('country', 'groups', 'pricing'));
   }
   public function createcreditor()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $groups = groups::get();
      $pricing = PriceGroup::get();
      return view('app.customers.creditor', compact('country', 'groups', 'pricing'));
   }

   public function details($id)
   {
      $customer = Customers::find($id);
      return view('app.customers.show', ['customer' => $customer]);
   }

   public function approvecreditor($id)
   {

      $c=Customers::whereId($id)->update(
         [
            'customer_type' => 'creditor',
            'is_creditor' => '1'
         ]
      );
      Session::flash('success', 'Customer successfully Approved');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Customer approved';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Customer successfully Approved';
      $activityLog->action = 'Customer '. $c->customer_name.' successfully Approved';
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return redirect()->route('creditors');
   }

   public function store(Request $request)
   {
      $this->validate($request, [
         'customer_name' => 'required',
         'id_number' => 'required',
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
      $user->region_id= Auth::user()->region_id;
      $user->business_code = Auth::user()->business_code;
      $user->password = Hash::make("password");
      $user->save();


      $customer = new customers;
      $customer->customer_name = $request->customer_name;
      $customer->account = $request->account;
      $customer->manufacturer_number = $request->manufacturer_number;
      $customer->user_code = $user->user_code;
      $customer->vat_number = $request->vat_number;
      $customer->delivery_time = $request->delivery_time;
      $customer->address = $request->address;
      $customer->postal_code = $request->postal_code;
      $customer->id_number = $request->id_number;
      $customer->customer_group = $request->customer_group;
      $customer->route = $request->route;
      $customer->route_code = $request->territory;
      $customer->zone_id = $request->territory;
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

      Session::flash('success', 'Customer successfully Added');

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
      $subregion_id = Area::whereId($customer->zone_id)->pluck('subregion_id')->implode('');
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
      $subregion_id = Area::whereId($customer->zone_id)->pluck('subregion_id')->implode('');
      $region_id = Subregion::whereId($subregion_id)->pluck('region_id')->implode('');
      $customer->update([
         'subregion_id' => $subregion_id,
         'region_id' => $region_id,
      ]);
      $groups = groups::get();
      $pricing = PriceGroup::get();
      return view('app.creditors.edit',
         compact('customer', 'country', 'regions', 'subregions', 'areas', 'groups', 'pricing')
      );
   }

   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'customer_name' => 'required'
      ]);

      $customer = customers::where('id', $id)->first();
      $customer->customer_name = $request->customer_name ?? $customer->customer_name;
      $customer->account = $request->account ?? $customer->account;
      $customer->manufacturer_number = $request->manufacturer_number ?? $customer->manufacturer_number;
      $customer->vat_number = $request->vat_number ?? $customer->vat_number;
      $customer->delivery_time = $request->delivery_time ?? $customer->delivery_time;
      $customer->address = $request->address ?? $customer->address;
      $customer->city = $request->city ?? $customer->city;
      $customer->province = $request->province ?? $customer->province;
      $customer->postal_code = $request->postal_code ?? $customer->postal_code;
      $customer->country = $request->country ?? $customer->country;
      $customer->latitude = $request->latitude ?? $customer->latitude;
      $customer->longitude = $request->longitude ?? $customer->longitude;
      $customer->contact_person = $request->contact_person ?? $customer->contact_person;
      $customer->telephone = $request->telephone ?? $customer->telephone;
      $customer->customer_group = $request->customer_group ?? $customer->customer_group;
      $customer->customer_secondary_group = $request->customer_secondary_group ?? $customer->customer_secondary_group;
      $customer->price_group = $request->price_group ?? $customer->price_group;
      $customer->route = $request->route ?? $customer->route;
      $customer->route_code = $request->territory ?? $customer->territory;
      $customer->zone_id = $request->territory ?? $customer->territory;
      $customer->branch = $request->branch ?? $customer->branch;
      $customer->email = $request->email ?? $customer->email;
      $customer->phone_number = $request->phone_number ?? $customer->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();


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

      return redirect()->route('creditors');
   }
   public function updatecreditor(Request $request, $id)
   {
      $this->validate($request, [
         'customer_name' => 'required'
      ]);

      $customer = customers::where('id', $id)->first();
      $customer->customer_name = $request->customer_name ?? $customer->customer_name;
      $customer->account = $request->account ?? $customer->account;
      $customer->manufacturer_number = $request->manufacturer_number ?? $customer->manufacturer_number;
      $customer->vat_number = $request->vat_number ?? $customer->vat_number;
      $customer->delivery_time = $request->delivery_time ?? $customer->delivery_time;
      $customer->address = $request->address ?? $customer->address;
      $customer->city = $request->city ?? $customer->city;
      $customer->province = $request->province ?? $customer->province;
      $customer->postal_code = $request->postal_code ?? $customer->postal_code;
      $customer->country = $request->country ?? $customer->country;
      $customer->latitude = $request->latitude ?? $customer->latitude;
      $customer->longitude = $request->longitude ?? $customer->longitude;
      $customer->contact_person = $request->contact_person ?? $customer->contact_person;
      $customer->telephone = $request->telephone ?? $customer->telephone;
      $customer->customer_group = $request->customer_group ?? $customer->customer_group;
      $customer->customer_secondary_group = $request->customer_secondary_group ?? $customer->customer_secondary_group;
      $customer->price_group = $request->price_group ?? $customer->price_group;
      $customer->route = $request->route ?? $customer->route;
      $customer->route_code = $request->territory ?? $customer->territory;
      $customer->zone_id = $request->territory ?? $customer->territory;
      $customer->branch = $request->branch ?? $customer->branch;
      $customer->email = $request->email ?? $customer->email;
      $customer->phone_number = $request->phone_number ?? $customer->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();


      Session::flash('success', 'Customer updated successfully');

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
