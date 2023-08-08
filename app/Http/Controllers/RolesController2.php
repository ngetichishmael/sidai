<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController2 extends Controller
{
   public function index()
   {
      $roles = Role::all();
      return view('app.roles.index', compact('roles'));
   }

   public function create()
   {
      $roles = Role::with('permissions' )->get();
      return view('app.roles.create', compact('roles'));
   }

   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required|unique:roles,name',
         'description' => 'required',
         'data_access_level' => 'required|in:all,regional,subregional,area',
      ]);
//dd($request->all());
      $role = Role::create($request->only('name', 'description', 'data_access_level'));

      // Attach permissions based on the role type (dashboard or api)
      $permissions = Permission::where('type', $role->data_access_level === 'api' ? 'api' : 'dashboard')->get();
      $role->permissions()->attach($permissions);

      // Associate data access level with the role
      $role->dataAccessLevels()->create(['data_access_level' => $role->data_access_level]);

      return redirect()->route('roles.create')->with('success', 'Role and permission(s) created successfully!');
   }

   public function update(Request $request, Role $role)
   {
      $request->validate([
         'name' => 'required|unique:roles,name,' . $role->id,
         'description' => 'required',
         'data_access_level' => 'required|in:regional,subregional,area',
      ]);

      $role->update($request->only('name', 'description', 'data_access_level'));

      // Update permissions based on the role type (dashboard or api)
      $permissions = Permission::where('type', $role->data_access_level === 'api' ? 'api' : 'dashboard')->get();
      $role->permissions()->sync($permissions);

      // Update data access level
      $roleDataAccess = $role->dataAccessLevels->first();
      if ($roleDataAccess) {
         $roleDataAccess->update(['data_access_level' => $role->data_access_level]);
      } else {
         $role->dataAccessLevels()->create(['data_access_level' => $role->data_access_level]);
      }
      return redirect()->route('app.roles.index')->with('success', 'Role updated successfully!');
   }
   public function destroy(Role $role)
   {
      $role->delete();
      $roles= Role::with('permissions' )->get();
      return redirect()->route('roles.create', compact('roles'))->with('success', 'Role deleted successfully!');
   }

}
