<?php

namespace App\Http\Controllers\app\products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockLiftController extends Controller
{
    public function lifted(){
        return view('app.stocks.liftedstock');
    }
}
