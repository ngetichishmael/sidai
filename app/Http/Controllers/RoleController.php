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
//      $regions=Region::select('id','name')->get();
//      $subregions=Subregion::select('id','name')->get();
//      $areas=Area::select('id','name')->get();
      $platform = [
         'Sales App' => 'Sales App',
         'Managers App' => 'Managers App',
         'Manager Dashboard' => 'Manager Dashboard',
         'Shop Attendee Dashboard' => 'Shop Attendee Dashboard',
         'Admin' => 'Admin',
      ];
     return view('app.roles.create', compact('roles', 'platform'));
   }
   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required|unique:roles',
         'display_name' => 'required|unique:roles',
      ]);
      if ($request->input('data_type')== "all"){
         $dataType=0;
      }
      $name = $request->input('name');
      $displayName = $request->input('display_name');
      $description = $request->input('description');
      $platforms = $request->input('platform');
      $dataType = $request->input('data_type');
      $role = new Role();
      $role->name = $name;
      $role->display_name = $displayName;
      $role->access_to = $dataType;
      $role->description = $description;
      $role->businessID =  auth()->user()->business_code;
      $role->sales_app = in_array('Sales App', $platforms) ? 'yes' : 'no';
      $role->managers_app = in_array('Managers App', $platforms) ? 'yes' : 'no';
      $role->manager_dashboard = in_array('Manager Dashboard', $platforms) ? 'yes' : 'no';
      $role->shop_attendee_dashboard = in_array('Shop Attendee Dashboard', $platforms) ? 'yes' : 'no';
      $role->admin = in_array('Admin', $platforms) ? 'yes' : 'no';
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
