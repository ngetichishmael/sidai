<?php

namespace App\Http\Controllers\app\finance\products;

use App\Http\Controllers\Controller;
use App\Models\finance\currency;
use App\Models\finance\products\attributes;
use App\Models\finance\products\product_gallery;
use App\Models\finance\products\product_information;
use App\Models\finance\products\product_inventory;
use App\Models\finance\products\product_price;
use App\Models\finance\tax;
use Auth;
use Finance;
use Helper;
use Illuminate\Http\Request;
use Input;
use Session;
use Wingu;

class variantsController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
   */
   public function index($id)
   {
      $productID = $id;
      $currency = currency::join('business','business.base_currency','=','currency.id')
                              ->where('business.id',Auth::user()->businessID)
                              ->first();

      $product = product_information::where('id',$id)->where('businessID',Auth::user()->businessID)->first();
      $variants = product_information::join('product_inventory','product_inventory.productID','=','product_information.id')
                     ->join('product_attributes','product_attributes.id','=','product_information.valueID')
                     ->join('product_price','product_price.productID','=','product_information.id')
                     ->join('product_gallery','product_gallery.productID','=','product_information.id')
                     ->where('product_information.parentID',$id)
                     ->where('product_information.businessID',Auth::user()->businessID)
                     ->select('*','product_attributes.value as value','product_information.id as prodID')
                     ->get();

      $taxes = tax::where('businessID',Auth::user()->businessID)->get();
      $values =  attributes::where('parentID',$product->attributeID)
                     ->where('businessID',Auth::user()->businessID)
                     ->pluck('value','id')
                     ->prepend('choose variant value','');
      $count = 1;

      return view('app.finance.products.variants.index', compact('productID','product','variants','taxes','count','values','currency'));
   }

   /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function create()
   {

   }

   /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
   public function store(Request $request, $id)
   {
      $this->validate($request,[
         'code_type' => 'required',
         'selling_price' => 'required',
         'buying_price' => 'required',
         'current_stock' => 'required',
         'variant' => 'required',
         'image' => 'required',
      ]);

      //product information
      $product = new product_information;
      if ($request->code_type == 'Auto') {
         $product->sku_code = Helper::generateRandomString(9);
      }elseif($request->code_type == 'Custom'){
         $product->sku_code = $request->sku_code;
      }
      $product->parentID = $id;
      $product->valueID = $request->variant;
      $product->attributeID = $request->attribute;
      $product->product_name = $request->name.'-'.Finance::products_attributes($request->variant)->value;
      $product->businessID = Auth::user()->businessID;
      $product->userID = Auth::user()->id;
      $product->save();

      //product price
      $price = new product_price;
      $price->productID = $product->id;
      $price->buying_price = $request->buying_price;
      $price->taxID = $request->taxID;
      $price->selling_price = $request->selling_price;
      $price->offer_price = $request->offer_price;
      $price->businessID = Auth::user()->businessID;
      $price->userID = Auth::user()->id;
      $price->save();

      //product quantities
      $inventory = new product_inventory;
      $inventory->productID = $product->id;
      $inventory->current_stock = $request->current_stock;
      $inventory->reorder_level = $request->reorder_level;
      $inventory->replenish_level = $request->replenish_level;
      $inventory->expiration_date = $request->expiration_date;
      $inventory->businessID = Auth::user()->businessID;
      $inventory->userID = Auth::user()->id;
      $inventory->save();

      //product image
      if(!empty($request->image)){

         //directory
         $directory = base_path().'/storage/files/business/'.Wingu::business(Auth::user()->businessID)->primary_email.'/finance/products/';

         //create directory if it doesn't exists
         if (!file_exists($directory)) {
            mkdir($directory, 0777,true);
         }

         //upload estimate to system
         $file = Input::file('image');

         // GET THE FILE EXTENSION
         $extension = $file->getClientOriginalExtension();

         // RENAME THE UPLOAD WITH RANDOM NUMBER
         $fileName = Helper::generateRandomString(9). '.' . $extension;

         // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
         $upload_success = $file->move($directory, $fileName);

         //save the image details into the database
         $image = new product_gallery;
         $image->productID = $product->id;
         $image->file_name = $fileName;
         $image->businessID = Auth::user()->businessID;
         $image->userID = Auth::user()->id;
         $image->cover = 1;
         //$image->file_size = $file->getSize();
         $image->file_mime = $file->getClientMimeType();
         $image->save();
      }

      Session::flash('success','Variant successfully added');

      return redirect()->back();
   }

   /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
   public function show($id)
   {

   }

   /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
   public function edit($parentID,$variantID)
   {
      $productID = $parentID;
      $product = product_information::where('id',$parentID)->where('businessID',Auth::user()->businessID)->first();
      $edit = product_information::join('product_inventory','product_inventory.productID','=','product_information.id')
                     ->join('product_attributes','product_attributes.id','=','product_information.valueID')
                     ->join('product_price','product_price.productID','=','product_information.id')
                     ->join('product_gallery','product_gallery.productID','=','product_information.id')
                     ->where('product_information.parentID',$parentID)
                     ->where('product_information.id',$variantID)
                     ->where('product_information.businessID',Auth::user()->businessID)
                     ->select('*','product_attributes.value as value','product_information.id as prodID','product_information.valueID as variant')
                     ->first();

      $taxes = tax::where('businessID',Auth::user()->businessID)->get();
      $values =  attributes::where('parentID',$product->attributeID)
                     ->where('businessID',Auth::user()->businessID)
                     ->pluck('value','id')
                     ->prepend('choose variant value','');
      $count = 1;

      return view('app.finance.products.variants.edit', compact('productID','product','edit','taxes','values'));
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
         'sku_code' => 'required',
         'selling_price' => 'required',
         'buying_price' => 'required',
         'current_stock' => 'required',
         'variant' => 'required',
      ]);


      //product information
      $product = product_information::where('businessID',Auth::user()->businessID)->where('id',$id)->first();
      $product->sku_code = $request->sku_code;
      $product->parentID = $product->parentID;
      $product->valueID = $request->variant;
      $product->attributeID = $request->attribute;
      $product->product_name = $request->name.'-'.Finance::products_attributes($request->variant)->value;
      $product->businessID = Auth::user()->businessID;
      $product->userID = Auth::user()->id;
      $product->save();

      //product price
      $price = product_price::where('businessID',Auth::user()->businessID)->where('productID',$id)->first();
      $price->productID = $product->id;
      $price->buying_price = $request->buying_price;
      $price->taxID = $request->taxID;
      $price->selling_price = $request->selling_price;
      $price->offer_price = $request->offer_price;
      $price->businessID = Auth::user()->businessID;
      $price->userID = Auth::user()->id;
      $price->save();

      //product quantities
      $inventory = product_inventory::where('businessID',Auth::user()->businessID)->where('productID',$id)->first();
      $inventory->productID = $product->id;
      $inventory->current_stock = $request->current_stock;
      $inventory->reorder_level = $request->reorder_level;
      $inventory->replenish_level = $request->replenish_level;
      $inventory->expiration_date = $request->expiration_date;
      $inventory->businessID = Auth::user()->businessID;
      $inventory->userID = Auth::user()->id;
      $inventory->save();

      //product image
      if(!empty($request->image)){

         //directory
         $directory = base_path().'/storage/files/business/'.Wingu::business(Auth::user()->businessID)->primary_email.'/finance/products/';

         //delete logo if exist
         $image = product_gallery::where('productID',$id)->where('cover',1)->first();
         if($image->file_name != ""){
            $delete = $directory.$image->file_name;
            if (File::exists($delete)) {
               unlink($delete);
            }
         }

         //create directory if it doesn't exists
         if (!file_exists($directory)) {
            mkdir($directory, 0777,true);
         }

         //upload estimate to system
         $file = Input::file('image');

         // GET THE FILE EXTENSION
         $extension = $file->getClientOriginalExtension();

         // RENAME THE UPLOAD WITH RANDOM NUMBER
         $fileName = Helper::generateRandomString(9). '.' . $extension;

         // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
         $upload_success = $file->move($directory, $fileName);

         //save the image details into the database
         $image = new product_gallery;
         $image->productID = $product->id;
         $image->file_name = $fileName;
         $image->businessID = Auth::user()->businessID;
         $image->userID = Auth::user()->id;
         $image->cover = 1;
         //$image->file_size = $file->getSize();
         $image->file_mime = $file->getClientMimeType();
         $image->save();
      }

      Session::flash('success','Variant successfully update');

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
     //
   }
}
