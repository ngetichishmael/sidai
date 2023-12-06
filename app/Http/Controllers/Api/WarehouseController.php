<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\warehousing;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
   {
      $region_id=$request->user()->region_id;
      if ($request->user()->account_type === 'NSM'){
         $warehouse = warehousing::where("status", "Active")
            ->orderBy('id', 'ASC')
            ->get();
      }else
         $warehouse = warehousing::where("status", "Active")->where('region_id', $region_id)
            ->orderBy('id', 'ASC')
            ->get();

      return response()->json([
         'status' => 200,
         'success' => true,
         'message' => "All warehouses available in Your Region",
         'data' => $warehouse,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
