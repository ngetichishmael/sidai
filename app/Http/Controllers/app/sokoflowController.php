<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;

class sokoflowController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      // $this->middleware('auth');
   }


   /**
    * dashboard controller instance.
    */
   public function dashboard()
   {


      return view('app.dashboard.dashboard');

   }

   //user summary
   public function user_summary()
   {
      return view('app.dashboard.user-summary');
   }
   public function allocated(){
      return view('app.dashboard.allocated');
   }
   public function allocatedusers(){
      return view('app.dashboard.allocatedusers');
   }
}
