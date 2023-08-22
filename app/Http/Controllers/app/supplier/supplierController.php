<?php

namespace App\Http\Controllers\app\supplier;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\country;
use App\Models\suppliers\category;
use App\Models\suppliers\suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class supplierController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }

   public function index()
   {
      return view('app.suppliers.index');
   }
   public function archiveView()
   {
      return view('app.suppliers.archived');
   }
   public function show()
   {
      return view('app.suppliers.index');
   }

   public function create()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id')->prepend('Choose Country', '');
      $groups = category::OrderBy('id', 'DESC')->pluck('name', 'id');
      return view('app.suppliers.create', compact('country', 'groups'));
   }

   public function store(Request $request)
   {
      $this->validate($request, [
         'email' => 'required',
         'phone_number' => 'required',
      ]);

      $primary = new suppliers;
      $primary->email = $request->email;
      $primary->name = $request->name;
      $primary->phone_number = $request->phone_number;
      $primary->telephone = $request->telephone;
      $primary->status = "Active";
      $primary->business_code = Auth::user()->business_code;
      $primary->save();

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Archive Distributor';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Adding Distributor to Sidai';
      $activityLog->action = 'User '. $request->user()->name. ' Role '.$request->user()->account_type.' added '. $primary->name .' Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      Session()->flash('success', 'Supplier has been successfully Added');

      return redirect()->route('supplier');
   }

   public function edit($id)
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id')->prepend('Choose Country', '');
      $suppliers = suppliers::where('suppliers.id', $id)
         ->first();
      //category
      $category = category::where('business_code', Auth::user()->business_code)->get();

      return view('app.suppliers.edit', compact('category', 'suppliers', 'country'));
   }

   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'email' => 'required',
         'phone_number' => 'required',
      ]);

      $edit = suppliers::where('id', $id)->first();
      $edit->email = $request->email;
      $edit->name = $request->name;
      $edit->phone_number = $request->phone_number;
      $edit->telephone = $request->telephone ?? $edit->telephone;
      $edit->status = $request->status ?? $edit->status ;
      $edit->save();

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Add Distributor';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'updating Distributor to Sidai';
      $activityLog->action = 'User '. $request->user()->name. ' Role '.$request->user()->account_type.' updated '. $edit->name .' Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      Session()->flash('success', 'Supplier has been successfully updated');

      return redirect()->back();
   }
   public function archive(Request $request, $id)
   {

      $edit = suppliers::where('id', $id)->first();
      $edit->status = "Inactive";
      $edit->save();

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Archive Distributor';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Adding Distributor to archive';
      $activityLog->action = 'User '. $request->user()->name. ' Role '.$request->user()->account_type.' archived '. $edit->name .' Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      Session()->flash('success', 'Supplier has been successfully archived');

      return redirect()->back();
   }
   public function activate(Request $request, $id)
   {

      $edit = suppliers::where('id', $id)->first();
      $edit->status = "Active";
      $edit->save();

      $random = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Unarchived Distributor';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Removed Distributor from archive';
      $activityLog->action = 'User '. $request->user()->name. ' Role '.$request->user()->account_type.' unarchived '. $edit->name .' Successfully';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      Session()->flash('success', 'Supplier has been successfully archived');

      return redirect()->back();
   }

   //delete permanently
   public function delete($id)
   {
   }
}
