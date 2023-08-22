<?php

namespace App\Http\Controllers\app\products;

use App\Http\Controllers\Controller;
use App\Models\products\brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;

class brandController extends Controller
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

      return view('app.products.brands.index');
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

      $check = brand::where('business_code', FacadesAuth::user()->business_code)->where('name',$request->name)->count();
      if($check == 0){
         $url = Str::slug($request->name);
      }else{
         $url = Str::slug($request->name).Str::random(3);
      }

      $brand = new brand;
      $brand->name = $request->name;
      $brand->url = $url;
      $brand->business_code = FacadesAuth::user()->business_code;
      // $brand->created_by = Auth::user()->user_code;
      $brand->save();

      session()->flash('success','You have successfully created a new brand.');

      return redirect()->route('product.brand');
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
      $brand = brand::find($id);
      $brands = brand::where('business_code',FacadesAuth::user()->business_code)->orderBy('id','desc')->get();
      $count = 1;
      return view('app.products.brands.edit', compact('brand','count','brands'));
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
         'name' => '',
      ]);

      $brand = brand::find($id);
      $brand->name = $request->name;
      // $brand->updated_by = Auth::user()->user_code;
      $brand->save();

      session()->flash('success','Brand successfully updated!');

      return redirect()->route('product.brand.edit',$brand->id);
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      $brand = brand::find($id);
      $brand->delete();

      session()->flash('success', 'The brand was successfully deleted !');

      return redirect()->route('product.brand');
   }
}
