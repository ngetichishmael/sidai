<?php

namespace App\Http\Controllers\app;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\business;
use App\Models\country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class settingsController extends Controller
{
   //account
   public function account(){
      $account = business::where('business_code',Auth::user()->business_code)->first();
      $country = country::pluck('name','name')->prepend('choose country','');
      return view('app.settings.account', compact('account','country'));
   }

   //update account
   public function update_account(Request $request){
      $this->validate($request,[
         'name' => 'required',
         'country' => 'required',
         'business_location' => 'required',
         'phone_number' => 'required',
      ]);

      $business = business::where('business_code',Auth::user()->business_code)->first();
      $business->name = $request->name;
      $business->country = $request->country;
      $business->business_location = $request->business_location;
      $business->phone_number = $request->phone_number;
      $business->save();

      //recorord activity
		$activities = Auth::user()->name.' Has updated the business details';
		$section = 'Settings';
		$action = 'Update';
      $business_code = Auth::user()->business_code;
		$activityID = $business->id;

		Helper::activity($activities,$section,$action,$activityID,$business_code);

      Session::flash('success','Business details updated successfully');

      return redirect()->back();
   }

   //activity_log
   public function activity_log(){
      $activities = activity_log::where('business_code',Auth::user()->business_code)->orderby('id','desc')->get();

      return view('app.settings.activity', compact('activities'));
   }
}
