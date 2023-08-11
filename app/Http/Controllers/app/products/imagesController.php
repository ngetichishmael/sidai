<?php
namespace App\Http\Controllers\app\products;

use App\Helpers\Helper as HelpersHelper;
use App\Http\Controllers\Controller;
use App\Models\file_manager;
use App\Models\products\product_information;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Session;
use Wingu;

class imagesController extends Controller{

   public function __construct(){
      $this->middleware('auth');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      //directory
		$directory = base_path().'/public/businesses/'.Wingu::business(Auth::user()->businessID)->businessID.'/finance/products/';

		//create directory if it doesn't exists
		if (!file_exists($directory)) {
			mkdir($directory, 0777,true);
		}

       //get file name
      $file = $request->file('file');

      $extension = $file->getClientOriginalExtension();
      $size =  $file->getSize();

      //change file name
      $filename = HelpersHelper::generateRandomString(40). '.' .$extension;

      //move file
      $file->move($directory, $filename);

      //save the image details into the database
      $image = new file_manager;
      $image->fileID     = $request->productID;
      $image->file_name  = $filename;
      $image->businessID = Auth::user()->businessID;
      $image->file_size  = $size;
      $image->folder 	 = 'Finance';
		$image->section 	 = 'products';
      $image->created_by = Auth::user()->id;
      $image->updated_by = Auth::user()->id;
      $image->file_mime  = $file->getClientMimeType();
      $image->save();
   }


   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $images = file_manager::where('fileID', $id)->where('businessID',Auth::user()->businessID)->where('section','products')->where('folder','Finance')->get();

      $product = Product_information::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      $count = 1;
      $productID = $id;

      return view('app.finance.products.images', compact('productID','images','count','product'));
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
      file_manager::where('fileID', $request->product_id)
                  ->where('businessID',Auth::user()->businessID)
                  ->where('cover', 1)
                  ->where('folder','Finance')
                  ->where('section','products')
                  ->update(['cover' => 0]);

      $cover = file_manager::where('id',$id)->where('section','products')->where('fileID', $request->product_id)->first();
      $cover->cover = 1;
      $cover->save();

      Session::flash('Success', 'Cover Images has been selected !');

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
      //delete image from folder/directory
      $oldimagename = file_manager::where('id','=',$id)->where('businessID',Auth::user()->businessID)->where('section','products')->select('file_name')->first();

      $directory = base_path().'/public/businesses/'.Wingu::business(Auth::user()->businessID)->businessID.'/finance/products/';
      $delete = $directory.$oldimagename->file_name;

      if (FacadesFile::exists($delete)) {
         unlink($delete);
      }

      //delete from database
      file_manager::where('id',$id)->where('section','products')->delete();

      session::flash('success','You have successfully Delete!');

      return redirect()->back();
   }
}
