<?php

namespace App\Http\Controllers\app;

use App\Helpers\Helper;
use Session;
use App\Models\country;
use App\Models\warehousing;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Auth;

class warehousingController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('app.warehousing.index');
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      $country = country::pluck('name', 'name')->prepend('choose country');

      return view('app.warehousing.create', compact('country'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request, [
         'name' => 'required',
         'phone_number' => 'required',
      ]);

      //check if has main
      if ($request->is_main == 'Yes') {
         $checkMain = warehousing::where('business_code', Auth::user()->business_code)->where('is_main', 'Yes')->count();
         if ($checkMain > 0) {
            warehousing::where('business_code', Auth::user()->business_code)->where('is_main', 'Yes')->update(['is_main' => NULL]);
         }
      }

      $warehouse = new warehousing;
      $warehouse->business_code = Auth::user()->business_code;
      $warehouse->warehouse_code = Str::random(20);
      $warehouse->name = $request->name;
      $warehouse->country = $request->country;
      $warehouse->city = $request->city;
      $warehouse->location = $request->location;
      $warehouse->phone_number = $request->phone_number;
      $warehouse->email = $request->email;
      $warehouse->longitude = $request->longitude;
      $warehouse->latitude = $request->latitude;
      $warehouse->status = $request->status;
      $warehouse->is_main = $request->is_main;
      $warehouse->created_by = Auth::user()->user_code;
      $warehouse->save();

      //recorord activity
      $activities = '<b>' . Auth::user()->name . '</b> Has <b>added</b> a new warehouse <i> ' . $request->name . '</i>';
      $section = 'Warehouse';
      $action = 'Create';
      $businessID = Auth::user()->business_code;
      $activityID = $warehouse->warehouse_code;

      Helper::activity($activities, $section, $action, $activityID, $businessID);

      Session()->flash('success', 'Warehouse added successfully');

      return redirect()->route('warehousing.index');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($code)
   {
      $country = country::pluck('name', 'name')->prepend('choose country');
      $edit = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->first();

      return view('app.warehousing.edit', compact('country', 'edit'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $code)
   {
      $this->validate($request, [
         'name' => 'required',
         'phone_number' => 'required',
      ]);

      //check if has main
      if ($request->is_main == 'Yes') {
         $checkMain = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->where('is_main', 'Yes')->count();
         if ($checkMain > 0) {
            warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->where('is_main', 'Yes')->update(['is_main' => NULL]);
         }
      }

      $warehouse = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->first();
      $warehouse->business_code = Auth::user()->business_code;
      $warehouse->name = $request->name;
      $warehouse->country = $request->country;
      $warehouse->city = $request->city;
      $warehouse->location = $request->location;
      $warehouse->phone_number = $request->phone_number;
      $warehouse->email = $request->email;
      $warehouse->longitude = $request->longitude;
      $warehouse->latitude = $request->latitude;
      $warehouse->status = $request->status;
      $warehouse->is_main = $request->is_main;
      $warehouse->updated_by = Auth::user()->user_code;
      $warehouse->save();

      //recorord activity
      $activities = '<b>' . Auth::user()->name . '</b> Has <b>Updated</b> warehouse details for <i> ' . $request->name . '</i>';
      $section = 'Warehouse';
      $action = 'Update';
      $businessID = Auth::user()->business_code;
      $activityID = $warehouse->warehouse_code;

      Helper::activity($activities, $section, $action, $activityID, $businessID);

      Session()->flash('success', 'Warehouse updated successfully');

      return redirect()->back();
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($code)
   {
      $checkIfMain = warehousing::where('business_code', Auth::user()->business_code)->where('warehouse_code', $code)->first();
      if ($checkIfMain->is_main != 'Yes') {
         return 'working on delete parameters';

         //recorord activity
         $activities = '<b>' . Auth::user()->name . '</b> Has <b>Deleted</b> warehouse <i> ' . $checkIfMain->name . '</i>';
         $section = 'Warehouse';
         $action = 'Update';
         $businessID = Auth::user()->business_code;
         $activityID = $checkIfMain->warehouse_code;

         Helper::activity($activities, $section, $action, $activityID, $businessID);
      } else {

         Session()->flash('warning', 'This warehouse is linked as the main warehouse, it can not the deleted');

         return redirect()->back();
      }

      return redirect()->back();
   }
}
