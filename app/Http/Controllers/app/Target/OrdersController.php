<?php

namespace App\Http\Controllers\app\Target;

use App\Models\User;
use App\Models\OrdersTarget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('app.Targets.orders');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('livewire.orders.layout');
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
      return view('livewire.orders.layout-view', [
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
        $edit = OrdersTarget::where('user_code', $code)->first();
        return view('app.Targets.ordersedit',['edit'=>$edit]);
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
        $updatesales = OrdersTarget::where('user_code',$code)->first();
        $updatesales->OrdersTarget = $request->target;
        $updatesales->Deadline = $request->deadline;
        $updatesales->save();

        Session::flash('success','Orders Target Updated!');
        return redirect()->route('order.target');
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
