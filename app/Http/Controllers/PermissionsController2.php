<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionsController2 extends Controller
{
   public function index()
   {
      $permissions = Permission::all();
      return view('permissions.index', compact('permissions'));
   }

   public function create()
   {
      return view('permissions.create');
   }

   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required|unique:permissions,name',
         'description' => 'required',
      ]);

      Permission::create($request->only('name', 'description'));

      return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
   }

   public function show(Permission $permission)
   {
      return view('permissions.show', compact('permission'));
   }

   public function edit(Permission $permission)
   {
      return view('permissions.edit', compact('permission'));
   }

   public function update(Request $request, Permission $permission)
   {
      $request->validate([
         'name' => 'required|unique:permissions,name,' . $permission->id,
         'description' => 'required',
      ]);

      $permission->update($request->only('name', 'description'));

      return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
   }

   public function destroy(Permission $permission)
   {
      $permission->delete();
      return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully!');
   }
}
