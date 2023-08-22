<?php

namespace App\Http\Controllers\app\finance\settings;

use App\Http\Controllers\Controller;
use App\Models\finance\salesorder\salesorder_settings;
use Auth;
use Finance;
use Illuminate\Http\Request;
use Session;
use Wingu;

class salesordersController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   public function index(){
      $check = salesorder_settings::where('businessID',Auth::user()->businessID)->count();
		if($check != 1){
			Finance::salesorder_setting_setup();
      }

      $settings = salesorder_settings::where('businessID',Auth::user()->businessID)->first();
      $count = 1;

      return view('app.finance.salesorders.settings.index', compact('settings','count'));
   }

   public function update_generated_number(Request $request, $id){
      $this->validate($request, [
         'number' => 'required',
         'prefix' => 'required',
      ]);

      $settings = salesorder_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->number = $request->number;
      $settings->prefix = $request->prefix;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Sales Order Number and Sales Order Prefix';
		$section = 'Settings';
		$type = 'Estimate Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Sales Order settings Updated successfully');

      return redirect()->back();
   }

   public function update_defaults(Request $request, $id){
      $settings = salesorder_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->default_terms_conditions = $request->default_terms_conditions;
      $settings->default_footer = $request->default_footer;
      $settings->default_customer_notes = $request->default_customer_notes;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Sales Order Terms and condition, Sales Order Footer and Customer Footer';
		$section = 'Settings';
		$type = 'Sales Order Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Sales Order settings Updated successfully');

      return redirect()->back();
   }

   public function update_tabs(Request $request, $id){

      $settings = salesorder_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->show_discount_tab = $request->show_discount_tab;
      $settings->show_tax_tab = $request->show_tax_tab;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Show Discount tab on invoice & Show Tax tab on invoice';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Sales Order settings Updated successfully');

      return redirect()->back();
   }

}
