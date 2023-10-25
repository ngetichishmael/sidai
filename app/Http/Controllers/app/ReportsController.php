<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use App\Models\Subregion;
use App\Models\User;
use App\Models\warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
   public $perPage = 50;
   public function reports(Request $request)
   {
      $routeName = $request->route()->getName();
      $middleware = $request->route()->middleware();
      $dataAccessLevel = Auth::user()->roles()->pluck('data_access_level')->first();
      if (in_array('web', $middleware)) {
         switch ($routeName) {
            // case 'preorders.reports':
            //    return view('app.Reports.preorders');
            // case 'vansales.reports':
            //    return view('app.Reports.vansales');
            case 'delivery.reports':
               return view('app.Reports.delivery');
            case 'sidai.reports':
               if (auth()->check() && in_array($dataAccessLevel, ['all', 'regional'])){
                  return view('app.Reports.users');
               }  else{
                  return redirect()->route('unauthorized');
               }

            case 'warehouse.reports':
               return view('app.Reports.warehouse');
            case 'supplier.reports':
               return view('app.Reports.supplier');
            // case 'visitation.reports':
            //    if (auth()->check() && in_array($dataAccessLevel, ['all', 'regional'])){
            //    return view('app.Reports.visitation');
            // }  else{
            //    return redirect()->route('unauthorized');
            // }
            case 'target.reports':
               return view('app.Reports.target');
            case 'payments.reports':
               return view('app.Reports.payments');
            case 'distributor.reports':
               return view('app.Reports.distributor');
            case 'regional.reports':
               return view('app.Reports.regional');
            case 'clients.reports':
               return view('app.Reports.customers');
            case 'inventory.reports':
               return view('app.Reports.inventory');
            default:
               return view('app.users.reports');
         }
      }

   }
   public function supplierDetails($id)
   {
      $orders = Orders::where('SupplierID', $id)->get();
      return view('app.items.supplier', ['orders' => $orders]);
   }

   public function preorderitems($order_code)
   {
      $items = Order_items::join('product_information', 'product_information.id', '=', 'order_items.productID')
            ->select(
                'order_items.id',
                'order_items.product_name as name',
                'product_information.sku_code as code'
            )
            ->groupBy('order_items.id')
            ->where('order_code', $order_code)->get();
      return view('app.items.preorder', ['items' => $items]);
   }
   public function vansaleitems($order_code)
   {
      $items = Order_items::join('product_information', 'product_information.id', '=', 'order_items.productID')
            ->select(
                'order_items.id',
                'order_items.product_name as name',
                'product_information.sku_code as code'
            )
            ->groupBy('order_items.id')
            ->where('order_code', $order_code)->get();
      return view('app.items.vansale', ['items' => $items]);
   }
   public function deliveryitems($order_code)
   {
      $items = Order_items::join('product_information', 'product_information.id', '=', 'order_items.productID')
            ->select(
                'order_items.id',
                'order_items.product_name as name',
                'product_information.sku_code as code'
            )
            ->groupBy('order_items.id')
            ->where('order_code', $order_code)->get();
      return view('app.items.delivery', ['items' => $items]);
   }
   public function tsr()
   {
      $tsrs = User::withCount('Orders')->where('account_type', 'TSR')->get();
      return view('app.items.tsr', ['tsrs' => $tsrs]);
   }
   public function customer()
   {
      $customers = customers::with(['Area', 'Area.Subregion', 'Area.Subregion.Region'])
         ->select('customers.customer_name', DB::raw('COUNT(orders.order_code) AS number_of_orders'), 'areas.name AS area_name', 'subregions.name AS subregion_name', 'regions.name AS region_name')
         ->leftJoin('orders', 'customers.id', '=', 'orders.customerID')
         ->leftJoin('order_items', 'orders.order_code', '=', 'order_items.order_code')
         ->leftJoin('areas', 'customers.route_code', '=', 'areas.id')
         ->leftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
         ->leftJoin('regions', 'subregions.region_id', '=', 'regions.id')
         ->groupBy('customers.customer_name', 'areas.name', 'subregions.name', 'regions.name')
         ->get();
      return view('app.items.customer', ['customers' => $customers]);
   }
   public function admin()
   {
      $admins = User::where('account_type', 'Admin')->get();
      return view('app.items.admin', ['admins' => $admins]);
   }
   public function rsm()
   {
      $rsms = User::withCount('Orders')->where('account_type', 'RSM')->get();
      return view('app.items.rsm', ['rsms' => $rsms]);
   }
   public function nsm()
   {
      $nsms = User::withCount('Orders')->where('account_type', 'NSM')->get();
      return view('app.items.nsm', ['nsms' => $nsms]);
   }
   public function shopattendee()
   {
      $attendee = User::withCount('Orders')->where('account_type', 'Shop-Attendee')->get();
      return view('app.items.attendee', ['attendee' => $attendee]);
   }
   public function paymentsDetails($id)
   {
      $order = Orders::whereId($id)->pluck('order_code')->implode('');
      return view('app.Reports.details', [
         'order_code' => $order
      ]);
   }
   public function products($code)
   {
      $count = 1;
      $warehouse = warehousing::where('warehouse_code', $code)->first();
      $products = product_information::with('Inventory', 'ProductPrice')->where('warehouse_code', $code)->paginate($this->perPage);
      return view('app.Reports.inventory', ['warehouses' => $warehouse, 'count' => $count, 'products' => $products]);
   }
   public function subregions($id)
   {
      $subregions = Subregion::where('region_id', $id)->get();
      $count = 1;
      return view('app.territories.subregions', ['subregions' => $subregions, 'count' => $count]);
   }
   public function routes($id)
   {
      $routes = Area::where('subregion_id', $id)->get();
      $count = 1;
      return view('app.territories.routes', ['routes' => $routes, 'count' => $count]);
   }
   public function customers($id)
   {
      $customers = customers::where('route', $id)->get();
      $count = 1;
      return view('app.territories.customers', ['count' => $count, 'customers' => $customers]);
   }

   public function productreport($code)
   {
      $warehouse = warehousing::where('warehouse_code', $code)->first();
      if (!empty($warehouse)) {
         $products = product_information::with('Inventory', 'ProductPrice')->where('warehouse_code', $code)->paginate($this->perPage);
         session(['warehouse_code' => $warehouse->warehouse_code]);
         return view('app.products.productreport', ['warehouse' => $warehouse, 'products' => $products]);
      }
   }
}
