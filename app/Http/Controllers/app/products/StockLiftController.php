<?php

namespace App\Http\Controllers\app\products;

use App\Http\Controllers\Controller;
use App\Models\inventory_allocated_items;
use Illuminate\Http\Request;

class StockLiftController extends Controller
{
    public function lifted(){
        return view('app.stocks.liftedstock');
    }
    public function items($allocation_code)
   {
      $items = inventory_allocated_items::where('allocation_code', $allocation_code)->get();
      return view('app.items.lifteditems', ['items' => $items]);
   }
}
