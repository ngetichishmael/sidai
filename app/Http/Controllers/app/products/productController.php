<?php

namespace App\Http\Controllers\app\products;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\activity_log;
use App\Models\Branches;
use App\Models\customers;
use App\Models\products\brand;
use App\Models\products\category;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\products\ProductSku;
use App\Models\RequisitionProduct;
use App\Models\suppliers\supplier_address;
use App\Models\suppliers\suppliers;
use App\Models\tax;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class productController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('app.products.index');
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
//      $code1 = $request->query('warehouse_code');
//      session(['warehouse_code' => $code1]);
         $code= session('warehouse_code');
      $categories = category::all()->pluck('name', 'id');
      $suppliers = suppliers::all()->pluck('name', 'id');
      $brands = brand::all()->pluck('name', 'id');

      return view('app.products.create', compact('categories', 'suppliers', 'brands', 'code'));
   }
   public function addCommonProducts()
   {
      $categories = category::all()->pluck('name', 'id');
      $suppliers = suppliers::all()->pluck('name', 'id');
      $brands = brand::all()->pluck('name', 'id');
      $user=Auth::user();
      $account='';
      $code='';
      if($user->account_type=='Shop-Attendee'){
         $wh=warehouse_assign::where('manager','=',$user->user_code)->select('warehouse_code')->first();
         $code=warehousing::where('warehouse_code',$wh->warehouse_code)->first();
         $account='s-a';
         if (empty($code)||$code==null){
            return redirect()->back()->withErrors("You have not been assigned a warehouse yet!!!");
         }
      }
      if ($user->account_type=='RSM'){
         $account='rsm';
         $code=[];
         $whs=warehousing::where('region_id','=',$user->region_id)->where('status', '=', 'Active')->select('warehouse_code', 'name')->get();
         foreach ($whs as $wh){
            $code[]= $wh;
         }
      }
      if ($user->account_type=='NSM' || $user->account_type=='Admin'){
         $whs=warehousing::where('status', '=', 'Active')->select('warehouse_code', 'name')->get();
         $account='nsm-admin';
         $code=[];
         foreach ($whs as $wh){
            $code[]= $wh;
         }
      }

      return view('app.products.createCommonProducts', compact('account','categories', 'suppliers', 'brands', 'code'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
   public function store(Request $request)
   {
      //Rule::unique('product_information', 'product_name')->ignore($request->id),'string',
      $this->validate($request, [
         'product_name' =>'required',
         'sku_code' => 'required',
         'buying_price' => 'required',
         'selling_price' => 'required',
         'distributor_price' => 'required',
         'image' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
      ]);
      $code= session('warehouse_code');
      $product = product_information::whereRaw('LOWER(product_name) = ?', [strtolower($request->product_name)])
         ->where('warehouse_code', '=', $code)
         ->first();
      if (!empty($product) || $product!=null){
         return redirect()->back()->withErrors("Product details already exist in the warehouse/store");
      }
      $image_path = $request->file('image')->store('image', 'public');
      $product_code = Str::random(20);
      $product = new product_information;
      $product->product_name = $request->product_name;
      $product->sku_code =  $request->sku_code ?? Str::random(20);
      $product->url = Str::slug($request->product_name);
      $product->brand = $request->brandID;
      $product->supplierID = $request->supplierID;
      $product->category = $request->category;
      $product->warehouse_code = $code;
      $product->image = $image_path;
      $product->active = $request->status;
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
      session()->flash('success', 'Product successfully added.');
         $random=rand(0,9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Creating Product';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Product '.$product->product_name .'added in warehouse'.$code;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

//      return redirect()->route('product.index');
      return redirect('/warehousing/'.$code.'/products');


   }
   public function StoreCommonProducts(Request $request)
   {
     // dd($request->all());
      //Rule::unique('product_information', 'product_name')->ignore($request->id),'string',
      if ($request->is_diffPrice==0) {
         $this->validate($request, [
            'product_name' => 'required',
            'sku_code' => 'required|unique:product_information',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'distributor_price' => 'required',
            'image' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
         ]);
      }else{
         $this->validate($request, [
            'product_name' => 'required',
            'sku_code' => 'required|unique:product_information',
            'diff_buying_price' => 'required',
            'diff_selling_price' => 'required',
            'diff_distributor_price' => 'required',
            'image' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
         ]);
      }
      if (Auth::user()->account_type ==='Shop-Attendee') {
         $product = product_information::whereRaw('LOWER(product_name) = ?', [strtolower($request->product_name)])
            ->where('warehouse_code', '=', $request->warehouse_code1)
            ->first();
         if (!empty($product) || $product != null) {
            return redirect()->back()->withErrors("Product details already exist in the warehouse/store");
         }
         $image_path = $request->file('image')->store('image', 'public');
         $product_code = Str::random(20);
         $product = new product_information;
         $product->product_name = $request->product_name;
         $product->sku_code = $request->sku_code ?? Str::random(20);
         $product->url = Str::slug($request->product_name);
         $product->brand = $request->brandID;
         $product->supplierID = $request->supplierID;
         $product->category = $request->category;
         $product->warehouse_code = $request->warehouse_code1;
         $product->image = $image_path;
         $product->active = $request->status;
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
      }else{
         foreach ($request->warehouse_codes as $key => $warehouse_code) {
            $product = product_information::whereRaw('LOWER(product_name) = ?', [strtolower($request->product_name)])
               ->where('warehouse_code', '=', $warehouse_code)
               ->first();
            if (!empty($product) || $product != null) {
               return redirect()->back()->withErrors("Product details already exist in the warehouse/store");
            }
            $image_path = $request->file('image')->store('image', 'public');
            $product_code = Str::random(20);
            $product = new product_information;
            $product->product_name = $request->product_name;
            $product->sku_code = $request->sku_code ?? Str::random(20);
            $product->url = Str::slug($request->product_name);
            $product->brand = $request->brandID;
            $product->supplierID = $request->supplierID;
            $product->category = $request->category;
            $product->warehouse_code = $warehouse_code;
            $product->image = $image_path;
            $product->active = $request->status;
            $product->track_inventory = 'Yes';
            $product->business_code = Auth::user()->business_code;
            $product->created_by = Auth::user()->user_code;
            $product->save();

            $priceFields = [
               'buying_price' => $request->buying_price,
               'selling_price' => $request->selling_price,
               'distributor_price' => $request->distributor_price,
            ];

            if ($request->is_diffPrice == 1) {
               $priceFields = [
                  'buying_price' => $request->diff_buying_price[$key],
                  'selling_price' => $request->diff_selling_price[$key],
                  'distributor_price' => $request->diff_distributor_price[$key],
               ];
            }

            product_price::updateOrCreate(
               [
                  'productID' => $product->id,
               ],
               [
                  'product_code' => $product_code,
                  'buying_price' => $priceFields['buying_price'],
                  'selling_price' => $priceFields['selling_price'],
                  'distributor_price' => $priceFields['distributor_price'],
                  'offer_price' => $priceFields['buying_price'],
                  'setup_fee' => $priceFields['selling_price'],
                  'taxID' => "1",
                  'tax_rate' => "0",
                  'default_price' => $priceFields['selling_price'],
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
         }
      }
      session()->flash('success', 'Product successfully added.');
         $random=rand(0,9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Creating Product';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Product '.$product->product_name .'added in warehouse'.$request->warehouse_code1 ?? '';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

//      return redirect()->route('product.index');
      return redirect('/warehousing/');


   }

   public function importProducts(Request $request)
   {
      $this->validate($request, [
         'upload_import' => 'required',
      ]);
      $code= session('warehouse_code');
      $product_code = Str::random(20);
      $filePath = $request->file('upload_import')->path();

      $spreadsheet = IOFactory::load($filePath);
      $worksheet = $spreadsheet->getActiveSheet();
      $rows = $worksheet->toArray();

      // Skip the first two rows as they contain headers
      for ($i = 2; $i < count($rows); $i++) {
         $row = $rows[$i];

         if (!empty(array_filter($row))) {
            $skuCode = (string) $row[0];
            $batchCode = (string) $row[0];
            $category = (string) $row[2];
            $units = (string) $row[3];
            $measure = (string) $row[3];
            $productName = (string) $row[4];
            $distributorPrice = (float) str_replace(',', '', $row[5]);
            $buyingPrice = (float) str_replace(',', '', $row[6]);
            $sellingPrice = (float) str_replace(',', '', $row[7]);

         $product = new product_information;
         $product->product_name = $productName;
         $product->url = Str::slug($productName);
         $product->sku_code = $skuCode;
         $product->batch_code = $batchCode;
         $product->category = $category;
         $product->units = $units;
         $product->measure = $measure;
         $product->warehouse_code = $code;
         $product->brand = "Sidai";
         $product->image = 'image/92Ct1R2936EUcEZ1hxLTFTUldcSetMph6OGsWu50.png';
         $product->active = "Active";
         $product->status = "Active";
         $product->track_inventory = 'Yes';
         $product->business_code = Auth::user()->business_code;
         $product->created_by = Auth::user()->user_code;
         $product->save();

         $productPrice = new product_price;
         $productPrice -> product_code=$product_code;
         $productPrice->productID = $product->id;
         $productPrice->distributor_price = $distributorPrice;
         $productPrice->buying_price = $buyingPrice;
         $productPrice->selling_price = $sellingPrice;
         $productPrice->offer_price = $buyingPrice;
         $productPrice->setup_fee = $sellingPrice;
         $productPrice->taxID = "1";
         $productPrice->tax_rate = "0";
         $productPrice->default_price = $sellingPrice;
         $productPrice->business_code = Auth::user()->business_code;
         $productPrice->created_by = Auth::user()->user_code;
         $productPrice->save();

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
      }
      }

      session()->flash('success', 'Products imported successfully.');
      $random=rand(0,9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Importing Products';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Products '.$product->product_name .'added in warehouse'.$code;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();
      return redirect('/warehousing/'.$code.'/products');
   }



   public function upload(Request $request)
   {
      $this->validate($request, [
         'excel_file' => 'required|mimes:xls,xlsx',
      ]);

      $file = $request->file('excel_file');
      $import = new ProductImport();
      Excel::import($import, $file);

      session()->flash('success', 'Products successfully imported.');

      return redirect()->route('products.index');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function details($id)
   {
      $details = product_information::join('business', 'business.id', '=', 'product_information.business_code')
         ->join('currency', 'currency.id', '=', 'business.base_currency')
         ->join('product_inventory', 'product_inventory.productID', '=', 'product_information.id')
         ->join('product_price', 'product_price.productID', '=', 'product_information.id')
         ->whereNull('parentID')
         ->where('product_information.id', $id)
         ->where('product_information.business_code', Auth::user()->business_code)
         ->select('*', 'product_information.id as proID', 'product_information.created_by as creator')
         ->orderBy('product_information.id', 'desc')
         ->first();

//      return $details;

      return view('app.products.details.show', compact('details'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $categories = category::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $suppliers = suppliers::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $brands = brand::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $product_information = product_information::whereId($id)->first();
      $product_price = product_price::where('productID', $id)->first();
      $product_inventory = product_inventory::where('productID', $id)->first();
      return view('app.products.edit', [
         'id' => $id,
         'brands' => $brands,
         'categories' => $categories,
         'brands' => $brands,
         'suppliers' => $suppliers,
         'product_information' => $product_information,
         'product_inventory' => $product_inventory,
         'product_price' => $product_price,
      ]);
   }
   public function restock($id)
   {
      $categories = category::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $suppliers = suppliers::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $brands = brand::where('business_code', Auth::user()->business_code)
         ->pluck('name', 'id');
      $product_information = product_information::whereId($id)->first();
      $product_price = product_price::where('productID', $id)->first();
      $product_inventory = product_inventory::where('productID', $id)->first();
      $code=$product_information->warehouse_code;


      return view('app.products.restock', [
         'id' => $id,
         'categories' => $categories,
         'brands' => $brands,
         'suppliers' => $suppliers,
         'product_information' => $product_information,
         'product_inventory' => $product_inventory,
         'product_price' => $product_price,
         'code'=>$code,
      ]);
   }
   public function singleview($id)
   {
      $product_information = product_information::whereId($id)->first();
      $product_price = product_price::where('productID', $id)->first();
      $product_inventory = product_inventory::where('productID', $id)->first();
      $code=$product_information->warehouse_code;


      return view('app.products.productview', [
         'id' => $id,
         'product_information' => $product_information,
         'product_inventory' => $product_inventory,
         'product_price' => $product_price,
         'code'=>$code,
      ]);
   }

   public function showBulkUpdateForm($warehouse){
      return view('app.products.productBulkPricesUpdateForm', [
         'warehouse'=>$warehouse
      ]);
   }
   public function updatesingle(Request $request, $id)
   {
      $information = product_information::whereId($id)->first();
      $this->validate($request, [
         'buying_price' => 'required',
         'distributor_price' => 'required'
      ]);
      $prices = product_price::where('id',$id)->first();
      $prices->buying_price = $request->buying_price;
      $prices->distributor_price = $request->distributor_price;
      $prices->selling_price = $request->selling_price ?? $prices->selling_price;
      $prices->business_code = Auth::user()->business_code;
      $prices->save();


      session()->flash('success', 'Prices successfully Updated!');
      $random=Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Price Updating';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Product '.$request->product_name .' successfully updated ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();


      return redirect('/warehousing/'.$information->warehouse_code.'/products');
   }

   public function updateBulk(Request $request, $warehouse)
   {
      $this->validate($request, [
         'excel_file' => 'required|file|mimes:xls,xlsx']);

      $path = $request->file('excel_file')->getRealPath();
      $data = Excel::toArray([], $path)[0];

      $userCode = Auth::user()->user_code;
      $businessCode = Auth::user()->business_code;

      foreach ($data as $row) {
         $productsku = $row['sku'];
         $buyingPrice = $row['wholesale'];
         $distributorPrice = $row['distributor'];
         $sellingPrice = $row['retail'];

         // Fetch product information based on product name and warehouse code
         $information = product_information::where('sku_code', $productsku)
            ->where('warehouse_code', $warehouse)
            ->first();

         if ($information) {
            $prices = product_price::updateOrCreate(
               ['id' => $information->id],
               [
                  'buying_price' => $buyingPrice ?? $prices->buying_price,
                  'distributor_price' => $distributorPrice ?? $prices->distributor_price,
                  'selling_price' => $sellingPrice ?? $prices->selling_price,
                  'business_code' => $businessCode,
               ]
            );
            $random = Str::random(20);
            $activityLog = new activity_log();
            $activityLog->activity = 'Price Updating';
            $activityLog->user_code = $userCode;
            $activityLog->section = 'web';
            $activityLog->action = 'Product ' . $productName . ' successfully updated ';
            $activityLog->userID = auth()->user()->id;
            $activityLog->activityID = $random;
            $activityLog->ip_address = $request->ip();
            $activityLog->save();
         }
      }

      session()->flash('success', 'Prices successfully Updated!');

      return redirect('/warehousing/products');
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function updatestock(Request $request, $id)
   {
      $information = product_information::whereId($id)->first();
      $this->validate($request, [
         'sku_codes' => 'required',
         'quantities' => 'required',
      ]);
      $skuCodes = $request->input('sku_codes');
      $quantities = $request->input('quantities');
      foreach ($skuCodes as $key => $skuCode) {
         $productInventory = product_inventory::where('productID', $id)->first();

         if ($productInventory) {
            $restockQuantity = $quantities[$key];
            $productInventory->current_stock += $restockQuantity;
            $productInventory->reorder_qty = $restockQuantity;
            $productInventory->save();

            $productSku = new ProductSku();
            $productSku->product_inventory_id = $productInventory->id;
            $productSku->warehouse_code = $information->warehouse_code;
            $productSku->sku_code = $skuCode;
            $productSku->restocked_quantity = $restockQuantity;
            $productSku->added_by = Auth::user()->user_code;
            $productSku->restocked_by = Auth::user()->user_code;
            $productSku->save();

            $information->updated_at = now();
            $information->save();

         }
      }

      session()->flash('success', 'Product successfully restocked!');
      $random=Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Product restocked';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web ';
      $activityLog->action = 'Product '.$request->product_name .' restocked';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();

//      return redirect()->back();
      return redirect('/warehousing/'.$information->warehouse_code.'/products');
   }
   public function update(Request $request, $id)
   {
      $information = product_information::whereId($id)->first();
      if ($information->image == null) {
         $this->validate($request, [
            'product_name' => 'required',
//            'buying_price' => 'required',
//            'selling_price' => 'required',
//            'image' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
         ]);
      }
      $this->validate($request, [
         'product_name' => 'required',
//         'buying_price' => 'required',
//         'selling_price' => 'required',
//         'image' => 'sometimes|mimes:png,jpg,bmp,gif,jpeg|max:5048',
      ]);
//      if ($request->has('image')) {
//         $image_path = $request->file('image')->store('image', 'public');
//      }
      product_information::updateOrCreate([
         'id' => $id,
         "business_code" => Auth::user()->business_code,
      ], [
         "product_name" => $request->product_name,
         "sku_code" => $request->sku_code,
//         "url" => Str::slug($request->product_name),
//         "brand" => $request->brandID,
         "supplierID" => $request->supplierID,
//         "category" => $request->category,
//         "image" => $image_path ?? $information->image,
//         "active" => $request->status,
         "track_inventory" => 'Yes',
//         "business_code" => Auth::user()->business_code,
         "updated_by" => Auth::user()->user_code,
      ]);


//      product_price::updateOrCreate(
//         [
//            'productID' => $id,
//         ],
//         [
//            'buying_price' => $request->buying_price,
//            'selling_price' => $request->selling_price,
//            'offer_price' => $request->buying_price,
//            'setup_fee' => $request->selling_price,
//            'taxID' => "1",
//            'tax_rate' => "0",
//            'default_price' => $request->selling_price,
//            'business_code' => Auth::user()->business_code,
//            'created_by' => Auth::user()->user_code,
//         ]
//      );

      product_inventory::updateOrCreate(
         [

            'productID' => $id,
         ],
         [
            'current_stock' => $request->current_stock,
            'reorder_point' => $request->reorder_point,
            'reorder_qty' => $request->reorder_qty,
            'expiration_date' => "None",
            'default_inventory' => "None",
            'notification' => 0,
//            'created_by' => Auth::user()->user_code,
            'updated_by' => Auth::user()->user_code,
            'business_code' => Auth::user()->business_code,
         ]

      );

      session()->flash('success', 'Product successfully restocked!');
      $random=Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Product updating';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Product '.$request->product_name .' successfully updated ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = $request->ip();
      $activityLog->save();

      return redirect('/warehousing/'.$information->warehouse_code.'/products');
   }

   public function approvestock($requisition_id){
      $requisition_products = RequisitionProduct::where('requisition_id',$requisition_id)->get();
      foreach ($requisition_products as $requisition_product){
         $approveproduct = product_information::whereId($requisition_product)->first();
         $approveproduct->is_approved = "Yes";
         $approveproduct->save();
      }
      session()->flash('success', 'Product successfully Approved !');
      $random=rand(0, 9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Approval';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'web';
      $activityLog->action = 'Product '.$approveproduct->product_name .' Successfully Approved  ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = '';
      $activityLog->save();

      return redirect()->route('inventory.approval');

   }


   /**
    * product description
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function description($id)
   {
      $product = product_information::where('id', $id)->first();
      $productID = $id;
      return view('app.products.description', compact('product', 'productID'));
   }


   /**
    * update product description
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function description_update(Request $request, $id)
   {

      $product = product_information::where('id', $id)->first();
      $product->short_description = $request->short_description;
      $product->description = $request->description;
      $product->business_code = Auth::user()->business_code;
      $product->updated_by = Auth::user()->user_code;
      $product->save();

      session()->flash('success', 'Item description updated successfully');

      return redirect()->back();
   }


   /**
    * product price
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function price($id)
   {
      $mainBranch = branches::where('businessID', Auth::user()->business_code)->first();
      $product = product_information::where('id', $id)->first();
      $defaultPrice = product_price::where('productID', $id)->where('default_price', 'Yes')->first();
      $prices = product_price::where('productID', $id)->get();
      $taxes = tax::where('businessID', Auth::user()->business_code)->get();
      $outlets = branches::where('businessID', Auth::user()->business_code)->get();
      $productID = $id;

      return view('app.products.price', compact('prices', 'taxes', 'productID', 'product', 'outlets', 'defaultPrice', 'mainBranch'));
   }


   /**
    * Update product price
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function price_update(Request $request, $id)
   {
      $this->validate($request, array(
         'buying_price' => 'required',
         'selling_price' => 'required'
      ));


      $price = product_price::where('id', $id)->where('business_code', Auth::user()->business_code)->first();

      $price->buying_price = $request->input('buying_price');
      $price->taxID = $request->input('taxID');
      $price->selling_price = $request->input('selling_price');
      $price->offer_price = $request->offer_price;

      $price->save();

      session()->flash('success', 'You have successfully edited item price!');

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
      // //check product in invoice
      // $invoice = invoice_products::where('productID', $id)->count();

      // if ($invoice == 0) {
      //    //delete image from folder/directory
      //    $check_image = ModelsFile_manager::where('fileID', $id)->where('business_code', Auth::user()->business_code)->where('folder', 'products')->count();

      //    if ($check_image > 0) {
      //       //directory
      //       $directory = base_path() . '/storage/files/business/' . Wingu::business(Auth::user()->business_code)->primary_email . '/finance/products/';
      //       $images = ModelsFile_manager::where('fileID', $id)->where('business_code', Auth::user()->business_code)->where('folder', 'products')->get();
      //       foreach ($images as $image) {
      //          if (File::exists($directory)) {
      //             unlink($directory . $image->file_name);
      //          }
      //          $image->delete();
      //       }
      //    }

      //    product_information::where('id', $id)->where('business_code', Auth::user()->business_code)->delete();
      //    product_inventory::where('productID', $id)->where('business_code', Auth::user()->business_code)->delete();
      //    //delete categories
      //    $categories = product_category_product_information::where('productID', $id)->get();
      //    foreach ($categories as $category) {
      //       product_category_product_information::find($category->id)->delete();
      //    }

      //    //delete tags
      //    $tags = product_tag::where('product_id', $id)->get();
      //    foreach ($tags as $tag) {
      //       product_tag::find($tag->id)->delete();
      //    }

      //delete price
      product_price::where('productID', $id)->where('business_code', Auth::user()->business_code)->delete();

      session()->flash('success', 'The Item was successfully deleted !');

      return redirect()->back();
      // } else {
      //    session()->flash('error', 'You have recorded transactions for this product. Hence, this product cannot be deleted.');
      //    return redirect()->back();
      // }
   }

   /**
    * get product price via ajax
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function productPrice(Request $request)
   {
      return json_encode(product_price::where('productID', $request->product)->first());
   }

   public function express_store(Request $request)
   {
      $primary = new customers();
      $primary->customer_name = $request->customer_name;
      $primary->business_code = Auth::user()->business_code;
      $primary->created_by = Auth::user()->user_code;
      $primary->save();

      $address = new supplier_address();
      $address->customerID = $primary->id;
      $address->save();
   }

   public function express_list(Request $request)
   {
      $accounts = product_information::where('business_code', Auth::user()->business_code)->orderby('id', 'desc')->get(['id', 'product_name as text']);
      return ['results' => $accounts];
   }
}
