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
       $this->middleware('auth');
   }


   /**
    * dashboard controller instance.
    */



   //user summary
   public function user_summary()
   {
      return view('app.dashboard.user-summary');
   }
   public function allocatedItems($allocation_code){
      return view('app.dashboard.allocated', ['allocationCode' => $allocation_code]);
   }
   public function allocatedusers(){
      return view('app.dashboard.allocatedusers');
   }
   public function dashboard()
   {
<<<<<<< HEAD


=======
>>>>>>> 7eda1ef9e0ccaeb5e46692de2c1fcb567180f1d0
      return view('app.dashboard.dashboard');

   }

}
