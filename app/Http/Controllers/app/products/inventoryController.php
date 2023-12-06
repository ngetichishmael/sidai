<?php
namespace App\Http\Controllers\app\products;
use App\Http\Controllers\Controller;
use App\Models\Branches;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use App\Models\ReconciledProducts;
use App\Models\Reconciliation;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Carbon\Carbon;
use Hr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
     $type= Str::lower(Auth::user()->account_type);
      if ($type == "shop-attendee") {
         $warehouse=warehouse_assign::where('manager', Auth::user()->user_code)->first();
         $sales=Reconciliation::where('warehouse_code',$warehouse->warehouse_code)
            ->with(['salesPerson','distributor:id,name','warehouse:warehouse_code,name','reconciliationProducts.productInformation:id,product_name'])
            ->get();
         $status = 'waiting_approval';
         $warehouse_name=warehousing::where('warehouse_code',$warehouse->warehouse_code)->first();
         return view('app.items.salespersons', ['type'=>$type,'status' => $status,'sales' => $sales, 'warehouse'=>$warehouse, 'warehouse_name'=>$warehouse_name->name]);
      }
      return view('app.stocks.reconciliation');
   }
   public function salesperson($warehouse_code)
   {
      $warehouse=$warehouse_code;
      $type= Str::lower(Auth::user()->account_type);
//      $sales = DB::table('reconciled_products')
//         ->join('product_information', 'reconciled_products.productID', '=', 'product_information.id')
//         ->join('users', 'reconciled_products.userCode', '=', 'users.user_code')
//         ->where('reconciled_products.warehouse_code', $warehouse)
//         ->select('users.name as user','reconciled_products.id as id','reconciled_products.created_at as date', DB::raw('SUM(reconciled_products.amount) as total_amount', ))
//         ->groupBy('users.name')
//         ->get();
//      $warehouse_name=warehousing::where('warehouse_code',$warehouse)->first();
//      return view('app.items.salespersons', ['sales' => $sales, 'warehouse'=>$warehouse, 'warehouse_name'=>$warehouse_name->name]);
      $sales=Reconciliation::with(['salesPerson','distributor:id,name','warehouse:warehouse_code,name','reconciliationProducts.productInformation:id,product_name'])
         ->get();
      $status = 'waiting_approval';
      $warehouse_name=warehousing::where('warehouse_code',$warehouse)->first();
      return view('app.items.salespersons', ['type'=>$type,'status' => $status,'sales' => $sales, 'warehouse'=>$warehouse, 'warehouse_name'=>$warehouse_name->name]);

   }
   public function reconciled($reconciliation_id)
   {
      $amounts=Reconciliation::where('reconciliation_code', $reconciliation_id)->first();
      $reconciled = DB::table('reconciled_products')
       ->where('reconciliation_code', $reconciliation_id)
      ->join('product_information', 'reconciled_products.productID', '=', 'product_information.id')
      ->join('users', 'reconciled_products.userCode', '=', 'users.user_code')
      ->select('product_information.product_name as name',
          'reconciled_products.amount as amount','users.name as user',
         'reconciled_products.updated_at as date')
      ->get();
      return view('app.items.reconciledproducts', ['reconciled' => $reconciled, 'amounts'=>$amounts]);
   }
   public function handleApprovals(Request $request, $reconciliation_id)
   {
      $reconciliation = Reconciliation::where('reconciliation_code',$reconciliation_id)->first();
      if (!$reconciliation) {
         return redirect()->back()->with("error", "Reconciliation not found");
      }        $id = $request->user()->id;
               $reconciled_products=ReconciledProducts::where('reconciliation_code',$reconciliation_id)->get();
      foreach ($reconciled_products as $data) {

         $id=DB::table('inventory_allocated_items')
            ->where('created_by', $data['userCode'])
            ->where('product_code', $data['productID'])
            ->decrement('allocated_qty', $data['amount'], [
               'updated_at' => now(),
            ]);

         DB::table('inventory_allocated_items')
            ->where('allocated_qty', '<', 1)
            ->delete();

         DB::table('product_inventory')
            ->where('created_by',$data['userCode'])
            ->increment('current_stock', $data['amount'], [
               'updated_at' => now(),
               'updated_by' => $id,
            ]);

         DB::table('order_payments')
            ->where('user_id', $data['userCode'])
            ->update(['isReconcile' => 'true']);
      }
      $reconciliation->update([
         'status' => $request->action,
         'note' => $request->note ?? $reconciliation->note,
         'approved_by'=>$request->user()->user_code,
         'approved_on'=>Carbon::now()
      ]);
      return redirect()->back()->with("success", "Reconciliation request".$request->action . " successful!");
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
