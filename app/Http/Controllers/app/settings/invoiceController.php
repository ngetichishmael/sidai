<?php

namespace App\Http\Controllers\app\finance\settings;

use App\Http\Controllers\Controller;
use App\Models\finance\invoice\invoice_settings;
use App\Models\wingu\status;
use Auth;
use Illuminate\Http\Request;
use Session;
use Wingu;

class invoiceController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   public function index(){
      $check = invoice_settings::where('businessID',Auth::user()->businessID)->count();
      if($check != 1){
         $create = new invoice_settings;
         $create->number = 0;
         $create->prefix = 'INV';
         $create->businessID = Auth::user()->businessID;
         $create->userID = Auth::user()->id;
         $create->save();
      }

      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $statuses = status::orderby('id','desc')->get();
      $count = 1;

      return view('app.finance.invoices.settings.index', compact('settings','statuses','count'));
   }

   public function update_generated_number(Request $request, $id){
      $this->validate($request, [
         'number' => 'required',
         'prefix' => 'required',
      ]);

      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->number = $request->number;
      $settings->prefix = $request->prefix;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Number and Invoice Prefix';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Invoice settings Updated successfully');

      return redirect()->back();
   }

   public function update_defaults(Request $request, $id){
      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->default_terms_conditions = $request->default_terms_conditions;
      $settings->default_invoice_footer = $request->default_invoice_footer;
      $settings->default_customer_notes = $request->default_customer_notes;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Terms and condition, Invoice Footer and Customer Footer';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Invoice settings Updated successfully');

      return redirect()->back();
   }

   public function update_workflow(Request $request, $id){

      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->editing_of_Sent = $request->editing_of_Sent;
      $settings->automatically_email_recurring = $request->automatically_email_recurring;
      $settings->auto_archive = $request->auto_archive;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Allow editing of Sent Invoice, Automatically email recurring invoices when they are created & Automatically email recurring invoices when they are created';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Invoice settings Updated successfully');

      return redirect()->back();
   }

   public function update_payments(Request $request, $id){

      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->notify_on_payment = $request->notify_on_payment;
      $settings->automate_thank_you_note = $request->automate_thank_you_note;
      $settings->auto_thank_you_payment_received = $request->auto_thank_you_payment_received;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Get notified when customers pay online, Do you want to include the payment receipt along with the Thank You Note? & Automate thank you note to customer on receipt of online payment';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Invoice settings Updated successfully');

      return redirect()->back();
   }

   public function update_tabs(Request $request, $id){

      $settings = invoice_settings::where('businessID',Auth::user()->businessID)->first();
      $settings->show_discount_tab = $request->show_discount_tab;
      $settings->show_tax_tab = $request->show_tax_tab;
      $settings->show_item_tax_tab = $request->show_item_tax_tab;
      $settings->save();

      //recorord activity
		$activities = Auth::user()->name.' Has made changes to the Invoice Show Discount tab on invoice & Show Tax tab on invoice';
		$section = 'Settings';
		$type = 'Invoice';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Invoice settings Updated successfully');

      return redirect()->back();
   }
}
