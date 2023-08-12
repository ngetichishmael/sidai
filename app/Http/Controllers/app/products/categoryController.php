<?php
namespace App\Http\Controllers\app\products;
use App\Http\Controllers\Controller;
use App\Models\products\category;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Input;
use Session;
use Wingu;

class categoryController extends Controller{

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
      $category = category::where('business_code',Auth::user()->business_code)->orderBy('id','desc')->get();
      $count = 1;
      return view('app.products.category.index', compact('category','count'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request,array(
         'name'=>'required',
      ));

      $check = category::where('business_code',Auth::user()->business_code)->where('name',$request->name)->count();
      if($check == 0){
         $url = Helper::seoUrl($request->name);
      }else{
         $url = Helper::seoUrl($request->name).Helper::generateRandomString(3);
      }

      $category = new category;
      $category->name = $request->name;
      $category->url = $url;
      $category->business_code = Auth::user()->business_code;
      $category->created_by = Auth::user()->id;
      $category->save();

      session::flash('success','You have successfully created a new Category.');

      return redirect()->route('product.category');
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
   public function edit($id)
   {
      $category = category::where('id',$id)->where('business_code',Auth::user()->business_code)->first();

      $categories = category::where('business_code',Auth::user()->business_code)->orderBy('id','desc')->get();
      $count = 1;
      return view('app.products.category.edit', compact('category','count','categories'));
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
         'name' => 'required',
      ]);

      $category = category::where('id',$id)->where('business_code',Auth::user()->business_code)->first();
      if($category->name != $request->name){
         $check = category::where('business_code',Auth::user()->business_code)->where('name',$request->name)->count();
         if($check == 0){
            $url = Helper::seoUrl($request->name);
         }else{
            $url = Helper::seoUrl($request->name).Helper::generateRandomString(3);
         }

         $category->url = $url;
      }
      $category->name = $request->name;
      $category->parentID = $request->parent;
      $category->updated_by = Auth::user()->id;
      $category->save();

      session::flash('success','Category successfully updated!');

      return redirect()->back();
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      category::where('id',$id)->where('business_code',Auth::user()->business_code)->delete();

      Session::flash('success', 'The category was successfully deleted !');

      return redirect()->route('product.category');
   }
}
