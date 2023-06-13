<?php

namespace App\Http\Controllers\app;

use App\Models\Area;
use App\Models\User;
use App\Models\Orders;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\warehousing;
use Illuminate\Http\Request;
use App\Models\customer\customers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order_items;
use Illuminate\Support\Facades\Auth;
use App\Models\products\product_information;

class ReportsController extends Controller
{
     public $perPage = 50;
    public function preorders()
    {
        $count =1;
        $preorders = Orders::with('User','Customer')->where('order_status', 'Pending Delivery')->where('order_type','Pre Order')->get();
         return view('app.reports.preorders',['preorders' => $preorders,'count'=>$count]);

    }
    public function preorderitems($order_code)
    {
      $items = Order_items::where('order_code',$order_code)->get();
      return view('app.items.preorder',['items'=>$items]);
      
    }
    public function vansaleitems($order_code)
    {
      $items = Order_items::where('order_code',$order_code)->get();
      return view('app.items.vansale',['items'=>$items]);
      
    }
    public function deliveryitems($order_code)
    {
      $items = Order_items::where('order_code',$order_code)->get();
      return view('app.items.delivery',['items'=>$items]);
      
    }
    public function vansales()
    {
          $count =1;
          $vansales = Orders::with('User','Customer')->where('order_status', 'Pending Delivery')->where('order_type','Van sales')->get();
         return view('app.reports.vansales',['vansales'=>$vansales,'count'=>$count]);

    }
    public function delivery()
    {
     $count =1;
     $deliveries = Orders::with('User','Customer')->where('order_status', 'Delivered')->get();
    return view('app.reports.delivery',['deliveries'=>$deliveries,'count'=>$count]);

    }
    public function users()
    {
     $usercount = User::whereNotNull('user_code')->select('account_type', DB::raw('COUNT(*) as count'))
     ->groupBy('account_type')
     ->get();
         return view('app.reports.users',['usercount'=>$usercount]);

    }

    public function warehouse()
    {$count =1;
     $warehouses = warehousing::whereNotNull('warehouse_code')->get();
    return view('app.reports.warehouse',['warehouses'=>$warehouses,'count'=>$count]);

    }
    public function distributor()
    {
     $count =1;
     $distributors = Orders::with('User','Customer')->where('supplierID','!=', '1')->where('supplierID','!=','NULL')->get();
         return view('app.reports.distributor',['distributors'=>$distributors,'count'=>$count]);

    }
    public function regional()
    {
     $regions = Region::all();
     $count =1;
     return view('app.reports.regional',['regions'=>$regions,'count'=>$count]);

    }
    public function inventory()
    {
     $warehouses= warehousing::whereNotNull('warehouse_code')->distinct('name')->get();
     $count=1;
         return view('app.reports.inventory',['warehouses'=>$warehouses,'count'=>$count]);
    }

    public function products($code)
    {
     $count=1;
     $warehouse= warehousing::where('warehouse_code',$code)->first();
     $products = product_information::with('Inventory', 'ProductPrice')->where('warehouse_code', $code)->paginate($this->perPage);
         return view('app.reports.inventory',['warehouses'=>$warehouse,'count'=>$count,'products'=>$products]);
    }
    public function subregions($id)
    {
     $subregions = Subregion::where('region_id',$id)->get();
     $count =1;
         return view('app.territories.subregions',['subregions'=>$subregions,'count'=>$count]);
    }
    public function routes($id)
    {
     $routes = Area::where('subregion_id',$id)->get();
     $count =1;
         return view('app.territories.routes',['routes' =>$routes,'count'=>$count]);
    }
    public function customers($id)
    {
     $customers = customers::where('route',$id)->get();
     $count = 1;
     return view('app.territories.customers',['count'=>$count,'customers'=>$customers]);
    }
    public function productreport()
    {
     $warehouses= warehousing::where('warehouse_code')->first();
      $products = product_information::where('warehouse_code');
     $count = 1;
     return view('app.products.productreport',['count'=>$count,'warehouses'=>$warehouses,'products'=>$products]);
    }

}
