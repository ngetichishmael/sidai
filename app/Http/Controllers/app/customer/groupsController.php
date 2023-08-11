<?php
// namespace App\Http\Controllers\app\finance\contact;

namespace App\Http\Controllers\app\customer;
use App\Http\Controllers\Controller;
use App\Models\customer\groups;
use Auth;
use Illuminate\Http\Request;
use Session;

class groupsController extends Controller
{
   public function __construct(){
		$this->middleware('auth');
   }

   /**
    * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function index()
   {
      $count = 1;
      $groups = groups::where('businessID',Auth::user()->business_code)->orderby('id','desc')->get();
      return view('app.finance.contacts.groups.index', compact('groups','count'));
   }


   /**
    * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
   public function store(Request $request)
   {
      $this->validate($request,[
         'name' => 'required'
      ]);

      $group = new groups;
      $group->name = $request->name;
      $group->status = $request->status;
      $group->businessID = Auth::user()->business_code;
      $group->created_by = Auth::user()->id;
      $group->save();

      Session::flash('success','Group added successfully');

      return redirect()->back();

   }

   /**
    * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
   public function edit($id)
   {
      $count = 1;
      $groups = groups::where('businessID',Auth::user()->business_code)->orderby('id','desc')->get();
      $edit = groups::where('businessID',Auth::user()->business_code)->where('id',$id)->first();

      return view('app.finance.contacts.groups.edit', compact('groups','count','edit'));
   }

   /**
    * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
   public function update(Request $request, $id)
   {
      $this->validate($request,[
         'name' => 'required'
      ]);

      $group = groups::where('businessID',Auth::user()->business_code)->where('id',$id)->first();
      $group->name = $request->name;
      $group->status = $request->status;
      $group->businessID = Auth::user()->business_code;
      $group->updated_by = Auth::user()->id;
      $group->save();

      Session::flash('success','Group updated successfully');

      return redirect()->back();
   }

   /**
    * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
   public function delete($id)
   {
      $group = groups::where('businessID',Auth::user()->business_code)->where('id',$id)->first();
      $group->delete();

      Session::flash('success','Group successfully deleted');

      return redirect()->route('finance.contact.groups.index');
   }
}
