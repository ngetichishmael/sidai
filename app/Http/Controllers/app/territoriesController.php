<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;

class territoriesController extends Controller
{
   //index
   public function index(){
      return view('app.territories.index');
   }
}
