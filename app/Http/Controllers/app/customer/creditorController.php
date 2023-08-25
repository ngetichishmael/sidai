<?php

namespace App\Http\Controllers\app\customer;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\Area;
use App\Models\country;
use App\Models\customer\customers;
use App\Models\customer\groups;
use App\Models\customer_group;
use App\Models\price_group;
use App\Models\PriceGroup;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\suppliers\supplier_address;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class creditorController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

   public function index()
   {
    return view('app.creditors.index');
   }
   public function details($id)
   {
      return view('app.creditors.show', ['id' => $id]);
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

      $customer = customers::where('id', $id)->first();
      $customer->customer_name = $request->customer_name ?? $customer->customer_name;
      $customer->contact_person = $request->contact_person;
      $customer->telephone = $request->telephone;
      $customer->price_group = $request->pricing_category ?? $customer->pricing_category;
      $customer->customer_group = $request->customer_group ?? $customer->customer_group;
      $customer->customer_secondary_group = $request->customer_secondary_group ?? $customer->customer_secondary_group;
      $customer->route = $request->territory ?? '';
      $customer->route_code = $request->territory ?? '';
      $customer->zone_id = $request->zone ?? '';
      $customer->region_id = $request->zone ?? '';
      $customer->customer_type = "creditor";
      $customer->approval = "approved";
      $customer->subregion_id = $request->region;
      $customer->branch = $request->branch ?? $customer->branch;
      $customer->email = $request->email ?? $customer->email;
      $customer->phone_number = $request->phone_number ?? $customer->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();


      Session::flash('success', 'Creditor updated successfully');

      return redirect()->route('creditor');
   }
   public function store(Request $request)
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
}
