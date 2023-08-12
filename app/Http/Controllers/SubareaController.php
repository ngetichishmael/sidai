<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Relationship;
use App\Models\Subarea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubareaController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('livewire.territory.subarea.index', [
         'areas' => Area::all()
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
         'area' => 'required',
      ]);
      Subarea::create([
         'area_id' => $request->area,
         'name' => $request->name,
         'primary_key' => Str::random(20)
      ]);
      $area = Area::whereId($request->area)->first();
      Relationship::create([
         'name' => $request->name,
         'has_children' => false,
         'region_id' => $area->Subregion->Region->id,
         'parent_id' => $request->area,
         'level_id' => 3,
      ]);
      Relationship::where('name', $area->name)->update([
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
