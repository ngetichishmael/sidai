<?php

namespace App\Http\Controllers\app\Target;

use App\Models\User;
use App\Models\VisitsTarget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class VisitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('app.Targets.visits');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('livewire.visits.layout');
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
     * @return \Illuminate\Http\Response
     */
    public function show($user_code)
   {
      $name = User::where('user_code', $user_code)->pluck('name')->implode('');
      return view('livewire.visits.layout-view', [
         'user_code' => $user_code,
         'name' => $name
      ]);
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $edit = VisitsTarget::where('user_code', $code)->first();
        return view('app.Targets.visitsedit',['edit'=>$edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $this->validate($request,[
            'target' => 'required',
            'deadline'=> 'required'
        ]);
        $updatesales = VisitsTarget::where('user_code',$code)->first();
        $updatesales->VisitsTarget = $request->target;
        $updatesales->Deadline = $request->deadline;
        $updatesales->save();

        Session::flash('success','Visits Target Updated!');
        return redirect()->route('visit.target');
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
