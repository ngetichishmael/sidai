<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Region;
use App\Models\Role;
use App\Models\Subregion;
use Illuminate\Http\Request;

class RoleController extends Controller
{
   public function create()
   {
      $roles=Role::all();
      $regions=Region::select('id','name')->get();
      $subregions=Subregion::select('id','name')->get();
      $areas=Area::select('id','name')->get();
      return view('app.roles.create', compact('roles','regions', 'subregions', 'areas'));
   }

   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required',
         'display_name' => 'required',
      ]);
      if ($request->input('data_type')== "all"){
         $dataType=0;
      }
      $name = $request->input('name');
      $displayName = $request->input('display_name');
      $description = $request->input('description');
      $platform = $request->input('platform');
      $dataType = $request->input('data_type');
      $region = $request->input('region');
      $subregion = $request->input('subregion');
      $area = $request->input('area');
      $role = new Role();
      $role->name = $name;
      $role->display_name = $displayName;
      $role->access_to = $dataType;
      $role->access_to_id = $region ?? $subregion ?? $area;
      $role->description = $description;
      $role->businessID =  auth()->user()->business_code;
      $role->platform = $platform;
      $role->created_by = auth()->user()->id;
      $role->updated_by = auth()->user()->id;
      $role->save();
      return redirect()->route('roles.create')->with('success', 'Role created successfully');
   }

   public function edit(Role $role)
   {
      return view('roles.edit', compact('role'));
   }

   public function update(Request $request, Role $role)
   {
      $validatedData = $request->validate([
         'name' => 'required',
         'display_name' => 'required',
         'description' => 'required',
         'businessID' => 'required',
      ]);
      $role->update($validatedData);
      return redirect()->route('roles.index')->with('success', 'Role updated successfully');
   }
}
