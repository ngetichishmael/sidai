<?php

namespace App\Http\Controllers\app\survey;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\survey\category;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class categoryController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
   }

   //category list
   public function index(){
      $categories = category::OrderBy('id','DESC')->get();
      $count = 1;
      return view('app.survey.category.index', compact('count','categories'));
   }

   public function create(){
      return view('app.survey.category.create');
   }

   public function store(Request $request){
      $this->validate($request, array(
         'title'=>'required',
         'status'=>'required',
      ));

      $category = new category;
      if(!empty($request->image)){
         $file = $request->image;
         // SET UPLOAD PATH
         $destinationPath = base_path().'/public/trivia/category/';
         // GET THE FILE EXTENSION
         $extension = $file->getClientOriginalExtension();

         // RENAME THE UPLOAD WITH RANDOM NUMBER
         $fileName = Helper::generateRandomString(10). '.' . $extension;
         // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
         $file->move($destinationPath, $fileName);
         $category->image = $fileName;
      }
      $category->title = $request->title;
      $category->status = $request->status;
      $category->description = $request->description;
      $category->created_by = Auth::user()->id;
      $category->save();

      session::flash('success','You have successfully created a new category.');

      return redirect()->route('trivia.category.index');
   }

   public function edit($id){
      $category = category::find($id);
      return view('app.survey.category.edit', compact('category'));
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
      $this->validate($request, array(
         'status'=>'required',
         'title' =>'required',
      ));

      $category = category::find($id);

      //delete and replaces image if exists
      if(!empty($request->image)){

         $old = category::where('id','=',$id)->select('image')->first();

         $directory = base_path().'/public/trivia/category/'.$old->image;

         // if (File::exists($directory)) {
         //    unlink($directory);
         // }
         if(file_exists($directory)) {
            unlink($directory);
         }

         $file = $request->image;
         // SET UPLOAD PATH
         $destinationPath = base_path().'/public/trivia/category/';
         // GET THE FILE EXTENSION
         $extension = $file->getClientOriginalExtension();
         // RENAME THE UPLOAD WITH RANDOM NUMBER
         $fileName = Helper::generateRandomString(). '.' . $extension;
         // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
         $upload_success = $file->move($destinationPath, $fileName);

         $category->image = $fileName;
      }

      $category->title = $request->title;
      $category->status = $request->status;
      $category->description = $request->description;
      $category->updated_by = Auth::user()->id;
      $category->save();

      session::flash('success','Category successfully updated');

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
      //delete image from folder/directory
      $old = category::where('id','=',$id)->select('image')->first();

      $directory = base_path().'/public/trivia/category/'.$old->image;

      if(file_exists($directory)) {
         unlink($directory);
      }

      $slider = category::find($id);

      $slider->delete();

      Session::flash('success', 'The category was successfully deleted !');

      return redirect()->route('trivia.category.index');
   }
}
