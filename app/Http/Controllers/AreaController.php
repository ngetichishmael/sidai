<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Relationship;
use App\Models\Subregion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AreaController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {

      return view('livewire.territory.area.index', [
         'subregions' => Subregion::all()
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
         'name' => 'required',
         'subregion' => 'required',
      ]);
      Area::create([
         'subregion_id' => $request->subregion,
         'name' => $request->name,
         'primary_key' => Str::random(20)
      ]);
      $subregion = Subregion::whereId($request->subregion)->first();
      Relationship::create([
         'name' => $request->name,
         'has_children' => false,
         'region_id' => $subregion->Region->id,
         'parent_id' => $request->subregion,
         'level_id' => 2,
      ]);
      Relationship::where('name', $subregion->name)->update([
         'has_children' => true,
      ]);
      Session()->flash('success', "Area successfully added");
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