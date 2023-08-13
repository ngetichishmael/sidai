<?php

namespace App\Http\Controllers\app\supplier;

use App\Http\Controllers\Controller;
use App\Models\suppliers\category;
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
      $categories = category::where('business_code',Auth::user()->business_code)->orderby('id','desc')->get();
      return view('app.suppliers.category.index', compact('categories','count'));
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

      $category = new category;
      $category->name = $request->name;
      $category->business_code = Auth::user()->business_code;
      $category->createdBy = Auth::user()->id;
      $category->updatedBy = Auth::user()->id;
      $category->save();

      Session::flash('success','Category added successfully');

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
      $categories = category::where('business_code',Auth::user()->business_code)->orderby('id','desc')->get();
      $edit = category::where('business_code',Auth::user()->business_code)->where('id',$id)->first();

      return view('app.suppliers.category.edit', compact('categories','count','edit'));
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

      $category = category::where('business_code',Auth::user()->business_code)->where('id',$id)->first();
      $category->name = $request->name;
      $category->business_code = Auth::user()->business_code;
      $category->updatedBy = Auth::user()->id;
      $category->save();

      Session::flash('success','Category updated successfully');

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
      $category = category::where('business_code',Auth::user()->business_code)->where('id',$id)->first();
      $category->delete();

      Session::flash('success','Category successfully deleted');

      return redirect()->route('supplier.category.index');
   }
}
