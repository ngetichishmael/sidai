<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserVisitController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index(): Response | View
   {
      return view('livewire.visits.users.index');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($user_code): Response | View
   {
      $name = DB::table('users')->where('users.user_code', $user_code)->pluck('name')->implode('');
      return view('livewire.visits.users.show', [
         'name' => $name,
         'user_code' => $user_code
      ]);
   }


}
