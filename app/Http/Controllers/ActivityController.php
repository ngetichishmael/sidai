<?php

namespace App\Http\Controllers;

use App\Models\activity_log;
use App\Models\User;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class ActivityController extends Controller
{
   use WithPagination;

   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
   {
      return view('livewire.activity.layout');
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
    */
   public function show($id)
   {
      $activity = activity_log::whereId($id)->first();
      $activities=activity_log::where('user_code',$activity->user_code )->get();
      $user =User::where('user_code', $activity->user_code)->get();
      return view('livewire.activity.view',compact('activity', 'activities','user'));
   }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
