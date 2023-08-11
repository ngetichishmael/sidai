<?php

namespace App\Http\Controllers\app\finance\settings;

use App\Http\Controllers\Controller;
use App\Models\finance\creditnote\creditnote_settings;
use Auth;
use Illuminate\Http\Request;
use Session;
use Wingu;

class creditnoteController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   public function index(){
      $check = creditnote_settings::where('businessID',Auth::user()->businessID)->count();
		if($check != 1){
			Finance::creditnote_setting_setup();
      }

      $settings = creditnote_settings::where('businessID',Auth::user()->businessID)->first();
      $count = 1;

      return view('app.finance.creditnote.settings.index', compact('settings','count'));

   }

   public function update_generated_number(Request $request, $id){
      $this->validate($request, [
         'number' => 'required',
         'prefix' => 'required',
      ]);

      $settings = creditnote_settings::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      $settings->number = $request->number;
      $settings->prefix = $request->prefix;
      $settings->userID = Auth::user()->id;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Credit Note Number and Credit Note Prefix';
		$section = 'Settings';
		$type = 'Credit Note Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Credit Note settings Updated successfully');

      return redirect()->back();
   }

   public function update_defaults(Request $request, $id){
      $settings = creditnote_settings::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      $settings->default_terms_conditions = $request->default_terms_conditions;
      $settings->default_footer = $request->default_footer;
      $settings->default_customer_notes = $request->default_customer_notes;
      $settings->userID = Auth::user()->id;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Credit Note Terms and condition, Credit Note Footer and Customer Footer';
		$section = 'Settings';
		$type = 'Credit Note Settings';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Credit Note settings Updated successfully');

      return redirect()->back();
   }

   public function update_tabs(Request $request, $id){

      $settings = creditnote_settings::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      $settings->show_discount_tab = $request->show_discount_tab;
      $settings->show_tax_tab = $request->show_tax_tab;
      $settings->userID = Auth::user()->id;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Credit Note Show Discount tab on Credit Note & Show Tax tab on invoice';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Credit Note settings Updated successfully');

      return redirect()->back();
   }

}
