<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\products\brand;
use App\Models\products\category;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\products\ProductSku;
use App\Models\suppliers\suppliers;
use App\Models\warehouse_assign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
      $warehouse=warehouse_assign::where('manager','=', Auth::user()->user_code)->first();
      if (!empty($warehouse)){
         $products =  product_information::where('warehouse_code', '=',$warehouse->warehouse_code)
            ->select('id','product_name', 'sku_code', 'warehouse_code','category','image')
            ->with(['ProductPrice' => function ($query) {
               $query->select('productID', 'selling_price as wholesale_price', 'buying_price as retail_price', 'distributor_price', 'offer_price');
               }])
            ->with(['Inventory' => function ($query) {
               $query->select('productID','current_stock');
               }])
            ->get();

         return response()->json([
            "success" => true,
            "message" => "All your warehouse products",
            "products" =>$products
         ], 200);
      }
      else
         return response()->json([
            "success" => false,
            "message" => "You are not assigned a warehouse store yet!!!",
         ], 404);
    }

   public function categories()
   {
      $categories = category::all()->pluck('name', 'id');
      return response()->json([
         "success" => true,
         "categories"=>$categories,
         ], 200);
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
       $warehouse=warehouse_assign::where('manager','=', Auth::user()->user_code)->first();
       if (!empty($warehouse)){
       $this->validate($request, [
          'product_name' =>'required',
          'buying_price' => 'required',
          'selling_price' => 'required',
          'distributor_price' => 'required',
          'image' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
       ]);
          $products=product_information::where('warehouse_code','=', $warehouse->warehouse_code)->get();
          foreach ($products as $p) {
             if (($p->product_name == $request->product_name) && ($p->sku_code == $request->sku_code) && ($p->warehouse_code == $warehouse->warehouse_code)) {
                return response()->json([
                   "success" => false,
                   "message" => "duplicate entry of product name/sku code",
                ], 409);
             }
             if (($p->sku_code == $request->sku_code) && ($p->warehouse_code == $warehouse->warehouse_code)) {
                return response()->json([
                   "success" => false,
                   "message" => "duplicate entry of product sku code",
                ], 409);
             }
          }
       $image_path = $request->file('image')->store('image', 'public');
       $product_code = Str::random(20);
          $product = new product_information;
       $product->product_name = $request->product_name;
       $product->sku_code =  $request->sku_code;
       $product->url = Str::slug($request->product_name);
       $product->brand = $request->brandID;
       $product->supplierID = $request->supplierID;
       $product->category = $request->category;
       $product->warehouse_code = $warehouse->warehouse_code;
       $product->image = $image_path;
       $product->active = "Active";
       $product->track_inventory = 'Yes';
       $product->business_code = Auth::user()->business_code;
       $product->created_by = Auth::user()->user_code;
       $product->save();

       product_price::updateOrCreate(
          [
             'productID' => $product->id,
          ],
          [
             'product_code' => $product_code,
             'buying_price' => $request->buying_price,
             'selling_price' => $request->selling_price,
             'distributor_price' => $request->distributor_price,
             'offer_price' => $request->buying_price,
             'setup_fee' => $request->selling_price,
             'taxID' => "1",
             'tax_rate' => "0",
             'default_price' => $request->selling_price,
             'business_code' => Auth::user()->business_code,
             'created_by' => Auth::user()->user_code,
          ]
       );

       product_inventory::updateOrCreate(
          [

             'productID' => $product->id,
          ],
          [
             'product_code' => $product_code,
             'current_stock' => 0,
             'reorder_point' => 0,
             'reorder_qty' => 0,
             'expiration_date' => "None",
             'default_inventory' => "None",
             'notification' => 0,
             'created_by' => Auth::user()->user_code,
             'updated_by' => Auth::user()->user_code,
             'business_code' => Auth::user()->business_code,
          ]

       );
       $random=rand(0,9999);
       $activityLog = new activity_log();
       $activityLog->activity = 'Creating Product';
       $activityLog->user_code = auth()->user()->user_code;
       $activityLog->section = 'Mobile';
       $activityLog->action = 'Product '.$product->product_name .'added in warehouse'.$warehouse->warehouse_code;
       $activityLog->userID = auth()->user()->id;
       $activityLog->activityID = $random;
       $activityLog->ip_address ="";
       $activityLog->save();
          return response()->json([
             "success" => true,
             "message" => "Product added successfully",
          ], 201);
       }
       else
          return response()->json([
             "success" => false,
             "message" => "You are not assigned a warehouse store yet!!!",
          ], 404);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
   public function details($sku_code)
   {
      $warehouse=warehouse_assign::where('manager','=', Auth::user()->user_code)->first();
      if (!empty($warehouse)){
      $details = product_information::
         join('product_inventory', 'product_inventory.productID', '=', 'product_information.id')
         ->join('product_price', 'product_price.productID', '=', 'product_information.id')
         ->where('product_information.sku_code', $sku_code)
         ->where('product_information.warehouse_code', $warehouse->warehouse_code)
         ->select('*', 'product_information.id as proID', 'product_information.created_by as creator')
         ->first();
         return response()->json([
            "success" => true,
            "message" => "Product details",
            "details"=>$details
         ], 200);
      }
      else
         return response()->json([
            "success" => false,
            "message" => "You are not assigned a warehouse store yet!!!",
         ], 404);
   }
   public function restock(Request $request, $sku_code)
   {
      $skuCodes = $request->input('sku_codes');
      $quantities = $request->input('quantities');
      $warehouse=warehouse_assign::where('manager','=', Auth::user()->user_code)->first();
      if (!empty($warehouse)){
      $information = product_information::where('sku_code',$skuCodes)->where('warehouse_code', $warehouse->warehouse_code)->first();
      $this->validate($request, [
         'sku_codes' => 'required',
         'quantities' => 'required',
      ]);
      $skuCodes = $request->input('sku_codes');
      $quantities = $request->input('quantities');
//      foreach ($skuCodes as $key => $skuCode) {
         $productInventory = product_inventory::where('productID', $information->id)->first();
         if ($productInventory) {
            $restockQuantity = $quantities;
            $productInventory->current_stock += $restockQuantity;
            $productInventory->reorder_qty = $restockQuantity;
            $productInventory->save();

            $productSku = new ProductSku();
            $productSku->product_inventory_id = $productInventory->id;
            $productSku->warehouse_code = $information->warehouse_code;
            $productSku->sku_code = $skuCodes;
            $productSku->restocked_quantity = $restockQuantity;
            $productSku->added_by = Auth::user()->user_code;
            $productSku->restocked_by = Auth::user()->user_code;
            $productSku->save();

            $information->updated_at = now();
            $information->save();

      $random=Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Product restocking';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile ';
      $activityLog->action = 'Product '.$request->product_name .' restocked ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();

            return response()->json([
               "success" => true,
               "message" => "Product restocked successfully",
            ], 201);
         }
         else
            return response()->json([
               "success" => false,
               "message" => "You are not assigned a warehouse store yet!!!",
            ], 404);
      }
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
