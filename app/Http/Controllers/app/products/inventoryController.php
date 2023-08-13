<?php
namespace App\Http\Controllers\app\products;
use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use Hr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class inventoryController extends Controller{

   public function __construct(){
      $this->middleware('auth');
   }

   /**
   * Display inventory
   **/
   public function inventory($id){
      $mainBranch = Branches::where('businessID', Auth::user()->business_code)->where('main_branch','Yes')->first();

      //product infromation
      $product = product_information::where('id',$id)->where('business_code',Auth::user()->business_code)->first();

      //get inventory per branch
      //$inventory = product_inventory::where('productID',$id)->where('business_code',Auth::user()->business_code)->first();

      //outlets
      $outlets = Branches::where('businessID',Auth::user()->business_code)->get();

      //default inventory
      $defaultInventory = product_inventory::where('productID',$id)->first();
      $productID = $id;
      return view('app.products.inventory', compact('defaultInventory','productID','product','outlets','mainBranch','mainBranch'));
   }

   /**
   * product inventory settings
   */
   public function inventory_settings(Request $request,$id){
      $product = product_information::where('id',$id)->where('business_code',Auth::user()->business_code)->first();
      $product->track_inventory = $request->track_inventory;
      $product->same_price = $request->same_price;
      $product->save();
      Session::flash('success','Item inventory successfully updated');

      return redirect()->back();
   }

   public function stockrecon(){

      return view('app.stocks.reconciliation');
   }
   public function reconciled($warehouse_code)
   {
      $reconciled = DB::table('reconciled_products')
      ->join('product_information', 'reconciled_products.productID', '=', 'product_information.id')
      ->join('users', 'reconciled_products.userCode', '=', 'users.user_code')
      ->where('reconciled_products.warehouse_code', $warehouse_code)
      ->select('product_information.product_name as name',
          'reconciled_products.amount as amount','users.name as user','reconciled_products.updated_at as date')
      ->get();

      return view('app.items.reconciledproducts', ['reconciled' => $reconciled]);
   }

   /**
   * update product inventory
   *
   * @return \Illuminate\Http\Response
   */
   public function inventroy_update(Request $request,$productID){
      $product = product_inventory::where('productID',$productID)->first();

      $product->current_stock = $request->current_stock;
      $product->reorder_point = $request->reorder_point;
      $product->reorder_qty = $request->reorder_qty;
      $product->expiration_date = $request->expiration_date;
      $product->business_code = Auth::user()->business_code;
      $product->updated_by = Auth::user()->id;
      $product->save();

      if($product->current_stock > $product->reorder_point){
         $update = product_inventory::where('productID',$productID)->first();
         $update->notification = 0;
         $update->save();
      }

      Session::flash('success','Item inventory successfully updated');

      return redirect()->back();
   }

   /**
   * link outlet to inventory
   */
   public function inventory_outlet_link(Request $request){
      $this->validate($request,[
         'productID' => 'required',
         'outlets' => 'required'
      ]);

      $defaultBranch = Branches::where('businessID',Auth::user()->business_code)->where('main_branch','Yes')->first();

      //add category
      $outlets = count(collect($request->outlets));
      if($outlets > 0){
         //upload new category
         for($i=0; $i < count($request->outlets); $i++ ){
            //check if outlet is linked
            if($defaultBranch->id != $request->outlets[$i]){
               $checkOutLet = product_inventory::where('productID',$request->productID)->where('branch_id',$request->outlets[$i])->where('business_code',Auth::user()->business_code)->count();
               if($checkOutLet == 0){
                  $out = new product_inventory;
                  $out->branch_id = $request->outlets[$i];
                  $out->productID = $request->productID;
                  $out->businessID = Auth::user()->business_code;
                  $out->created_by = Auth::user()->id;
                  $out->updated_by = Auth::user()->id;
                  $out->save();
               }

               $checkOutLet = product_price::where('productID',$request->productID)->where('branch_id',$request->outlets[$i])->where('business_code',Auth::user()->business_code)->count();
               if($checkOutLet == 0){
                  //link outlet to price
                  $priceOutlet = new product_price;
                  $priceOutlet->productID = $request->productID;
                  $priceOutlet->branch_id = $request->outlets[$i];
                  $priceOutlet->businessID = Auth::user()->business_code;
                  $priceOutlet->updated_by = Auth::user()->id;
                  $priceOutlet->created_by = Auth::user()->id;
                  $priceOutlet->save();
               }
            }
         }
      }

      Session::flash('success','Item successfully link to outlet');

      return redirect()->back();
   }

   /**
   * Delete inventroy link
   */
   public function delete_inventroy($productID,$branchID){
      $inventroy = product_inventory::where('productID',$productID)->where('branch_id',$branchID)->where('business_code',Auth::user()->business_code)->first();
      if($inventroy->current_stock == "" || $inventroy->current_stock == 0 ){
         product_inventory::where('productID',$productID)->where('branch_id',$branchID)->where('business_code',Auth::user()->business_code)->delete();
         product_price::where('productID',$productID)->where('branch_id',$branchID)->where('business_code',Auth::user()->business_code)->delete();
         Session::flash('success','Product successfully deleted');
         return redirect()->back();
      }else{
         Session::flash('warning','make sure you dont have any item in the location before deleting');

         return redirect()->back();
      }
   }

}
