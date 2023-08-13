<?php

namespace App\Http\Controllers\app\finance\settings;

use App\Http\Controllers\Controller;
use App\Models\finance\quotes\quote_settings;
use Auth;
use Illuminate\Http\Request;
use Session;
use Wingu;

class quoteController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   public function index(){
      $check = quote_settings::where('businessID',Auth::user()->businessID)->count();
		if($check != 1){
			Finance::quote_setting_setup();
      }

      $settings = quote_settings::where('businessID',Auth::user()->businessID)->first();
      $count = 1;

      return view('app.finance.quotes.settings.index', compact('settings','count'));
   }

   public function update_generated_number(Request $request, $id){
      $this->validate($request, [
         'number' => 'required',
         'prefix' => 'required',
      ]);

      $settings = quote_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->number = $request->number;
      $settings->prefix = $request->prefix;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Quote Number and Quote Prefix';
		$section = 'Settings';
		$type = 'Quote Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Quote settings Updated successfully');

      return redirect()->back();
   }

   public function update_defaults(Request $request, $id){
      $settings = quote_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->default_terms_conditions = $request->default_terms_conditions;
      $settings->default_footer = $request->default_footer;
      $settings->bcc = $request->bcc;
      $settings->default_customer_notes = $request->default_customer_notes;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Quote Terms and condition, Quote Footer and Customer Footer';
		$section = 'Settings';
		$type = 'Quote Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Quote settings Updated successfully');

      return redirect()->back();
   }

   public function update_tabs(Request $request, $id){

      $settings = quote_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->show_discount_tab = $request->show_discount_tab;
      $settings->show_tax_tab = $request->show_tax_tab;
      $settings->save();

      //recorded activity
		$activities = Auth::user()->name.' Has made changes to the Quote Show Discount tab on Quote & Show Tax tab on Quote';
		$section = 'Settings';
		$type = 'Quote';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Quote settings Updated successfully');

      return redirect()->back();
   }

}
