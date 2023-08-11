<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\activity_log;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function index()
    {
//        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
//        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('name', 'id');
       $roles = Role::all();
        return view('app.roles.create', compact('permissions', 'roles'));
    }

    public function store(StoreRoleRequest $request)
    {
       $name = $request->input('name');
       $displayName = $request->input('display_name');
       $dataAccessLevel = $request->input('data_access_level');
       $role = new Role();
       $role->name = $name;
       $role->description = $displayName;
       $role->data_access_level = $dataAccessLevel;
       $role->created_by = $request->user()->user_code;
       $role->save();
//        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('platform', []));
       $permissions = Permission::all()->pluck('name', 'id');
       $roles = Role::with('permissions')->get();
       $random = Str::random(20);
       $activityLog = new activity_log();
       $activityLog->activity = 'Creating A role and Permissions';
       $activityLog->user_code = auth()->user()->user_code;
       $activityLog->section = 'Creating role';
       $activityLog->action = 'Role ' . $displayName . 'Created a by ' . auth()->user()->name;
       $activityLog->userID = auth()->user()->id;
       $activityLog->activityID = $random;
       $activityLog->ip_address = "";
       $activityLog->save();
        return redirect()->route('roles.create', compact('permissions', 'roles'))->with('success', 'Role and permission(s) created successfully!');
    }

    public function edit(Role $role)
    {
//        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('name', 'id');
        $role->load('permissions');

        return view('app.roles.edit', compact('permissions', 'role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
       $this->validate($request, [
          'name' => 'required',
          'permission' => 'required',
       ]);
       $role->update($request->only('name', 'data_access_level'));
       // $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('roles.create');
    }

    public function show(Role $role)
    {
//        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('app.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
//        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return back()->with('success', 'Role deleted successfully!');
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT)->with('success', 'Roles deleted successfully!');
    }
}
