<?php

namespace App\Http\Controllers\app;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
