<?php

namespace App\Http\Controllers\app\finance\settings;

use App\Http\Controllers\Controller;
use App\Models\finance\tax;
use Auth;
use Illuminate\Http\Request;
use Session;
use Wingu;

class taxesController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   public function index(){
      $taxes = tax::where('businessID',Auth::user()->businessID)->orderby('id','desc')->get();
      $count = 1;
      return view('app.finance.taxes.index', compact('taxes','count'));
   }

   public function store(Request $request){
      $this->validate($request, [
         'tax_name' => 'required',
         'tax_rate' => 'required',
      ]);

      $tax = new tax;
      $tax->name = $request->tax_name;
      $tax->rate = $request->tax_rate;
      $tax->compound = $request->tax_rate / 100;
      $tax->description = $request->description;
      $tax->businessID = Auth::user()->businessID;
      $tax->created_by = Auth::user()->id;
      $tax->save();


      //recorord activity
		$activities = Auth::user()->name.' Has added a new tax rate';
		$section = 'Settings';
		$type = 'Tax rates';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $tax->id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Tax rate successfully added');

      return redirect()->route('finance.settings.taxes');
   }

   /**
    * edit leave
    *
    * @return \Illuminate\Http\Response
   */
   public function edit($id){
      $data = tax::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      return response()->json(['data' => $data]);
   }

   public function update(Request $request){
      $this->validate($request, [
         'name' => 'required',
         'rate' => 'required',
      ]);

      $tax = tax::where('id',$request->taxID)->where('businessID',Auth::user()->businessID)->first();
      $tax->name = $request->name;
      $tax->rate = $request->rate;
      $tax->compound = $request->rate/100;
      $tax->description = $request->description;
      $tax->businessID = Auth::user()->businessID;
      $tax->updated_by = Auth::user()->id;
      $tax->save();


      //recorord activity
		$activities = Auth::user()->name.' Has made a tax rate update';
		$section = 'Settings';
		$type = 'Tax rates';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $request->taxID;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Tax rate successfully updated');

      return redirect()->route('finance.settings.taxes');
   }

   public function delete($id){
      tax::find($id)->delete();

      //recorord activity
		$activities = Auth::user()->name.' Has deleted a tax rate';
		$section = 'Settings';
		$type = 'Tax rates';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);

      Session::flash('success','Tax rate successfully deleted');

      return redirect()->back();
   }

   public function store_express(Request $request){
      $tax = new tax;
      $tax->name = $request->tax_name;
      $tax->rate = $request->rate;
      $tax->compound = $request->rate/100;
      $tax->businessID = Auth::user()->businessID;
      $tax->created_by = Auth::user()->id;
      $tax->save();


      //recorord activity
		$activities = Auth::user()->name.' Has added a tax rate update';
		$section = 'Settings';
		$type = 'Tax rates';
      $adminID = Auth::user()->id;
      $businessID = Auth::user()->businessID;
		$activityID = $tax->id;

		Wingu::activity($activities,$section,$type,$adminID,$activityID,$businessID);
   }

   public function express_list(){
      $taxes = tax::where('businessID',Auth::user()->businessID)->orderby('id','desc')->get(['id', 'rate as text']);
      return ['results' => $taxes];
   }
}
