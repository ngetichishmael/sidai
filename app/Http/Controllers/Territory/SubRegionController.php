<?php

namespace App\Http\Controllers\Territory;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Relationship;
use App\Models\Subregion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubRegionController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('livewire.territory.sub-region.index', [
         'regions' => Region::all()
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
      $this->validate($request, [
         'name' => 'required|unique:subregions',
         'region' => 'required',
      ]);
      $subregion = Subregion::create([
         'region_id' => $request->region,
         'name' => $request->name,
         'primary_key' => Str::random(20)
      ]);
      $region = Region::whereId($request->region)->first();
      Relationship::create([
         'name' => $request->name,
         'has_children' => false,
         'region_id' => $subregion->region_id,
         'parent_id' => $request->region,
         'level_id' => 1,
      ]);
      Relationship::where('name', $region->name)->update([
         'has_children' => true,
      ]);
      Session()->flash('success', "Sub region successfully added");
      return redirect()->back();
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
