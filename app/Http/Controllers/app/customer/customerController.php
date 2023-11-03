<?php

namespace App\Http\Controllers\app\customer;
use File;
use App\Models\Area;
use App\Models\User;
use App\Models\Region;
use App\Models\country;
use App\Models\Subregion;
use App\Models\PriceGroup;
use App\Models\price_group;
use Illuminate\Support\Str;
use App\Models\activity_log;
use Illuminate\Http\Request;
use App\Models\customer_group;
use Illuminate\Support\Carbon;
use App\Models\customer\groups;
use App\Models\customer\customers;
use App\Models\suppliers\suppliers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\suppliers\supplier_address;
use Illuminate\Support\Facades\Auth as FacadesAuth;

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
   public function approveCustomers()
   {
      return view('app.customers.approve');
   }
   public function dispproved()
   {
      return view('app.customers.disapproved');
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

      return view('app.customers.show', ['id' => $id,]);
   }

   public function handleApproval(Request $request)
   {
      $selectedCustomers = $request->input('selected_customers');
      if (empty($selectedCustomers)) {
         Session()->flash('error','No Customer selected');
         return Redirect::back();
      }else{
         foreach ($selectedCustomers as $selectedCustomer) {
            if ($request->has('approve')) {
               Customers::where('id', $selectedCustomer)->update(['approval' => 'Approved']);
            } elseif ($request->has('disapprove')) {
               Customers::where('id', $selectedCustomer)->update(['approval' => 'Disapproved']);
            }
         }
      }

      if ($request->has('approve')){
         Session()->flash('success','Successfully Approved Customers');
         return redirect('approveCustomers');
      } elseif ($request->has('disapprove')) {
         Session()->flash('success','Successfully Disapproved Customers');
         return redirect('approveCustomers');
      }
      Session()->flash('success','Successfully Approved Customers');
      return redirect('approveCustomers');
   }


   public function approvecreditor(Request $request,$id)
   {
      $c=Customers::whereId($id)->update(
         [
            'customer_type' => 'creditor',
            'creditor_status' => 'waiting_approval',
            'is_creditor' => 1,
         ]
      );
      Session::flash('success', 'Customer successfully Approved');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Customer approved';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Web';
      $activityLog->action = 'Customer With id'. $c.' successfully Approved';
      $activityLog->activityID = $random;
      $activityLog->ip_address =$request->ip();
      $activityLog->save();

      return redirect()->route('creditor');
   }
   public function approvecustomer(Request $request, $id)
   {
      $c=Customers::whereId($id)->update(
         [
            'approval' => 'approved'
         ]
      );
      Session::flash('success', 'Customer successfully Approved');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Customer approved';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Web';
      $activityLog->action = 'Customer With id'. $c.' successfully Approved';
      $activityLog->activityID = $random;
      $activityLog->ip_address=$request->ip();
      $activityLog->save();

      return redirect()->route('customer');
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
      $customer->region_id = $request->route;
      $customer->subregion_id = $request->route;
      $customer->route_code = $request->route;
      $customer->zone_id = $request->route;
      $customer->status = "Active";
      $customer->email = $request->email;
      $customer->customer_type = "normal";
      $customer->phone_number = $request->phone_number;
      $customer->business_code = FacadesAuth::user()->business_code;
      $customer->created_by = FacadesAuth::user()->user_code;
      $customer->save();

      if (($request->customer_group!=null) && (($request->customer_group ==='Distributor')|| ($request->customer_group ==='Distributors'))) {
         $primary = new suppliers;
         $primary->email = $request->email;
         $primary->name = $request->customer_name;
         $primary->phone_number = $request->phone_number;
         $primary->telephone = $request->telephone;
         $primary->customer_id=$customer->id;
         $primary->status = "Active";
         $primary->business_code = Auth::user()->business_code;
         $primary->save();
      }
      Session::flash('success', 'Customer successfully Added');
      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Creating customer';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Web';
      $activityLog->action = 'Customer ' . $customer->customer_name . ' successfully Created';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();

      return redirect()->route('customer');
   }
   public function storecreditor(Request $request)
   {
      $this->validate($request, [
         'customer_name' => 'required',
         'id_number' => 'required',
      ]);
      $customerNameWithoutSpaces = str_replace(' ', '', $request->customer_name);
      $emailData = $request->email ?? $customerNameWithoutSpaces . Str::random(3) . '@gmail.com';
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

      if (($request->customer_group!=null) && (($request->customer_group ==='Distributor')|| ($request->customer_group ==='Distributors'))) {
         $primary = new suppliers;
         $primary->email = $emailData;
         $primary->name = $request->customer_name;
         $primary->phone_number = $request->phone_number;
         $primary->telephone = $request->telephone;
         $primary->customer_id=$customer->id;
         $primary->status = "Active";
         $primary->business_code = Auth::user()->business_code;
         $primary->save();
      }
//      $emailData = $request->email == null ? null : $request->email;
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
      $user->password = Hash::make("password");
      $user->save();

      Session::flash('success', 'Customer successfully Creditor Added');

      return redirect()->route('creditor');
   }

   public function editapprove( $id)
   {
      $regions = Region::all();
      $subregions = Subregion::all();
      $areas = Area::all();
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $customer = customers::where('customers.id', $id)
         ->select('*', 'customers.id as customerID')
         ->first();
      $regions = Region::all();
      $groups = groups::get();
      $prices = PriceGroup::get();
      return view('app.customers.editapprove',
         compact('customer', 'country', 'groups', 'prices','regions','subregions','areas')
      );
   }
   public function edit( $id)
   {
      $regions = Region::all();
      $subregions = Subregion::all();
      $areas = Area::all();
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id');
      $customer = customers::where('customers.id', $id)
         ->select('*', 'customers.id as customerID')
         ->first();
      $groups = groups::get();
      $prices = PriceGroup::get();
      return view('app.customers.edit',
         compact('customer', 'country', 'groups', 'prices','regions','subregions','areas')
      );
   }

   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'customer_name' => 'required',
         'phone_number' => 'required',
         'email' => 'required',
         'pricing_category' => 'required',
         'customer_group' => 'required',
      ]);
      $customer = customers::find($id);
      if (!$customer) {
         return redirect()->back()->with('error', 'Customer not found');
      }
   $cname=$customer->customer_name;
   $phone=$customer->phone_number;
//   dd($request->all());
      $customer->update([
         'customer_name' => $request->input('customer_name')??$customer->customer_name,
         'id_number' => $request->input('id_number')??$customer->id_number,
         'contact_person' => $request->input('contact_person')??$customer->contact_person,
         'telephone' => $request->input('telephone' )??$customer->telephone,
         'address' => $request->input('address')??$customer->address,
         'price_group' => $request->input('pricing_category')??$customer->price_group,
         'customer_group' => $request->input('customer_group')??$customer->customer_group,
         'route' => $request->input('territory')??$customer->route,
         'route_code' => $request->input('territory')??$customer->route_code,
         'region_id' => $request->input('zone')??$customer->region_id,
         'subregion_id' => $request->input('region')??$customer->subregion_id,
         'zone_id' => $request->input('territory')??$customer->zone_id,
         'branch' => $request->input('branch') ?? $customer->branch,
         'email' => $request->input('email') ?? $customer->email,
         'phone_number' => $request->input('phone_number')??$customer->phone_number,
         'updated_at'=>now(),
         'updated_by'=>auth()->user()->user_code,
      ]);

      // Check for Distributor
      if (($request->input('customer_group') === 'Distributor') || ($request->input('customer_group') === 'Distributors')) {
         $supplier = suppliers::where('name', $cname)
            ->where('phone_number', $phone)
            ->first();
         if ($supplier) {
            $supplier->update([
               'email' => $request->input('email'),
               'phone_number' => $request->input('phone_number')??$phone,
               'telephone' => $request->input('telephone')??$phone,
               'customer_id'=>$customer->id,
               'status' => 'Active',
               'name' => $request->input('customer_name')??$cname,
               'business_code' => auth()->user()->business_code,
               'updated_at'=>now(),
               'updated_by'=>auth()->user()->user_code,
            ]);
         } else {
            suppliers::create([
               'email' => $request->input('email'),
               'phone_number' => $request->input('phone_number')??$phone,
               'telephone' => $request->input('telephone')??$phone,
               'customer_id'=>$customer->id,
               'status' => 'Active',
               'name' => $request->input('customer_name')??$cname,
               'business_code' => auth()->user()->business_code,
               'updated_at'=>now(),
               'updated_by'=>auth()->user()->user_code,
            ]);
         }
      }
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
      $activityLog->section = 'Web';
      $activityLog->action = 'Customer ' . $customer->customer_name . ' successfully ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();
if ($request->input('in')=='approve'){
   return redirect()->route('approvecustomers');
}elseif ($request->input('in')=='customer'){
      return redirect()->route('customer');
   }else {
   return redirect()->route('customer');
}
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
