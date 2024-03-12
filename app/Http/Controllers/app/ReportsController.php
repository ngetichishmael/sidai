<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Order_items;
use App\Models\order_payments;
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
   public function dailyReports(Request $request)
   {
      $routeName = $request->route()->getName();
      $middleware = $request->route()->middleware();
      $dataAccessLevel = Auth::user()->roles()->pluck('data_access_level')->first();
      if (in_array('web', $middleware)) {
         switch ($routeName) {
            case 'stockist.reports':
               return view('app.DailyReports.stockist');
            default:
               return view('app.DailyReport.stockist');
         }
      }

   }
   public function reports(Request $request)
   {
      $routeName = $request->route()->getName();
      $middleware = $request->route()->middleware();
      $dataAccessLevel = Auth::user()->roles()->pluck('data_access_level')->first();
      if (in_array('web', $middleware)) {
         switch ($routeName) {
            case 'employee.reports':
               return view('app.Reports.employee');
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
   public function order_details($code)
    {
        $order = Orders::where('order_code', $code)->with('User')->first();
        $items = Order_items::where('order_code', $order->order_code)->get();
        $sub = Order_items::select('sub_total')->where('order_code', $order->order_code)->get();
        $total = Order_items::select('total_amount')->where('order_code', $order->order_code)->get();
        $Customer_id = Orders::select('customerID')->where('order_code', $code)->first();
        $id = $Customer_id->customerID;
        $test = customers::where('id', $id)->first();
        // dd($test->id);
        $payment = order_payments::where('order_id', $order->order_code)->first();
        // dd($payment);
        return view('app.orders.report_order_details', compact('order', 'items', 'test', 'payment', 'sub', 'total'));
    }
    public function regional_order($id){
      $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = customers::whereIn('route_code', $areas)->pluck('id');
        $orders = Orders::whereIn('customerID', $customers)->get();
      //   return $orders ?? 0;
      return view('products.regional_order_details',['orders'=>$orders]);
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
                'order_items.quantity as quantity',
                'order_items.created_at as date',
                'product_information.sku_code as code'
            )
            ->groupBy('order_items.id')
            ->where('order_code', $order_code)->get();
      return view('app.items.delivery', ['items' => $items]);
   }
   public function tsr()
   {
      $tsrs = User::withCount('Orders')->where('account_type', 'TSR')
      ->where('route_code', '=', Auth::user()->route_code)
      ->get();
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
      $rsms = User::withCount('Orders')->where('account_type', 'RSM')
      ->where('route_code', '=', Auth::user()->route_code)->get();
      return view('app.items.rsm', ['rsms' => $rsms]);
   }
   public function nsm()
   {
      $nsms = User::withCount('Orders')->where('account_type', 'NSM')
      ->where('route_code', '=', Auth::user()->route_code)
      ->get();
      return view('app.items.nsm', ['nsms' => $nsms]);
   }
   public function shopattendee()
   {
      $attendee = User::withCount('Orders')->where('account_type', 'Shop-Attendee')
      ->where('route_code', '=', Auth::user()->route_code)
      ->get();
      return view('app.items.attendee', ['attendee' => $attendee]);
   }
   public function paymentsDetails($id)
   {
      $order = Orders::whereId($id)->pluck('order_code')->implode('');
      return view('app.Reports.details', [
         'order_code' => $order
      ]);
   }

   public function target_details($id)
   {
      $results = DB::table('users AS u')
    ->where('u.id', $id) 
    ->leftJoin('leads_targets AS lt', 'u.user_code', '=', 'lt.user_code')
    ->leftJoin('orders_targets AS ot', 'u.user_code', '=', 'ot.user_code')
    ->leftJoin('sales_targets AS st', 'u.user_code', '=', 'st.user_code')
    ->leftJoin('visits_targets AS vt', 'u.user_code', '=', 'vt.user_code')
    ->where('u.account_type', '!=', 'Customer')
    ->select(
        'u.id AS id',
        'u.name AS user_name',
        'u.account_type AS user_type',
        'lt.LeadsTarget AS leads_target',
        'lt.AchievedLeadsTarget AS leads_achieved',
        'ot.OrdersTarget AS orders_target',
        'ot.AchievedOrdersTarget AS orders_achieved',
        'st.SalesTarget AS sales_target',
        'st.created_at AS created_at',
        'st.AchievedSalesTarget AS sales_achieved',
        'vt.VisitsTarget AS visits_target',
        'vt.AchievedVisitsTarget AS visits_achieved'
    )
    
    ->get();
      return view('targets.details',['results'=>$results]);
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
