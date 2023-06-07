<?php

namespace App\Http\Controllers\app;

use App\Models\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function preorders()
    {
        $count =1;
        $preorders = Orders::all()->where('order_status', 'Pending Delivery')->where('order_type','Pre Order');
         return view('app.reports.preorders',['preorders' => $preorders,'count'=>$count]);

    }
    public function vansales()
    {
         return view('app.reports.vansales');

    }
    public function delivery()
    {
         return view('app.reports.delivery');

    }
    public function users()
    {
         return view('app.reports.users');

    }

    public function warehouse()
    {
         return view('app.reports.warehouse');

    }
    public function distributor()
    {
         return view('app.reports.distributor');

    }
    public function regional()
    {
         return view('app.reports.regional');

    }
    public function inventory()
    {
         return view('app.reports.inventory');

    }
}
