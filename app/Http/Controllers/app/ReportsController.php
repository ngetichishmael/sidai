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
use Illuminate\Support\Facades\Auth;
use App\Models\products\product_information;

class ReportsController extends Controller
{
    public function preorders()
    {
        $count =1;
        $preorders = Orders::all()->where('order_status', 'Pending Delivery')->where('order_type','Pre Order');
        $users = User::where('business_code',Auth::user()->business_code)->pluck('name');
         return view('app.reports.preorders',['preorders' => $preorders,'count'=>$count,'users'=>$users]);

    }
    public function vansales()
    {
          $count =1;
          $vansales = Orders::all()->where('order_status', 'Pending Delivery')->where('order_type','Van sales');
         return view('app.reports.vansales',['vansales'=>$vansales,'count'=>$count]);

    }
    public function delivery()
    {
     $count =1;
     $deliveries = Orders::all()->where('order_status', 'Delivered');
    return view('app.reports.delivery',['deliveries'=>$deliveries,'count'=>$count]);

    }
    public function users()
    {
     $count=1;
     $users = User::whereNotNull('user_code')->distinct('account_type')->pluck('account_type');
     $usercount = User::select('account_type', DB::raw('COUNT(*) as count'))
     ->groupBy('account_type')
     ->get();
         return view('app.reports.users',['users'=>$users,'count'=>$count,'usercount'=>$usercount]);

    }

    public function warehouse()
    {$count =1;
     $warehouses = warehousing::all()->whereNotNull('warehouse_code');
    return view('app.reports.warehouse',['warehouses'=>$warehouses,'count'=>$count]);

    }
    public function distributor()
    {
         return view('app.reports.distributor');

    }
    public function regional()
    {
     $regions = Region::all();
     $count =1;
         return view('app.reports.regional',['regions'=>$regions,'count'=>$count]);

    }
    public function inventory()
    {
     $warehouses= warehousing::whereNotNull('warehouse_code')->distinct('name')->pluck('name');
     $count=1;
         return view('app.reports.inventory',['warehouses'=>$warehouses,'count'=>$count]);

    }
    public function subregions()
    {
     $subregions = Subregion::all();
     $count =1;
         return view('app.territories.subregions',['subregions'=>$subregions,'count'=>$count]);
    }
    public function routes()
    {
     $routes = Area::paginate(10);
     $count =1;
         return view('app.territories.routes',['routes' =>$routes,'count'=>$count]);
    }
    public function customers()
    {
     $customers = customers::all();
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
