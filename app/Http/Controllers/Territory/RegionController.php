<?php

namespace App\Http\Controllers\Territory;

use App\Http\Controllers\Controller;
use App\Imports\RegionalImport;
use App\Models\Region;
use App\Models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RegionController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('livewire.territory.region.index');
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
      ]);
      $region = Region::create([
         'name' => $request->name,
         'primary_key' => Str::random(20)
      ]);
      Relationship::create([
         'name' => $request->name,
         'has_children' => false,
         'region_id' => $region->id,
         'parent_id' => null,
         'level_id' => 0,
      ]);
      Session()->flash('success', "Region successfully added");
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
      $this->validate($request, [
         'upload_import' => 'required'
      ]);
      $file = request()->file('upload_import');

      Excel::import(new RegionalImport, $file);

      Session()->flash('success', 'Regional imported Successfully.');

      return redirect()->route('regions');
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
