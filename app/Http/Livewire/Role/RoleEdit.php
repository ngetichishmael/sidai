<?php

namespace App\Http\Livewire\Role;

use App\Models\activity_log;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;
use Livewire\Component;

class RoleEdit extends Component
{
    public $role;
    public $name;
    public $description;
    public $data_access_level;
    public $selectedPermissions = [];
    public $allPermission = false;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->description = $role->description;
        $this->data_access_level = $role->data_access_level;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    public function render()
    {
        $permissions = Permission::get();

        return view('livewire.role.role-edit', [
            'permissions' => $permissions,
        ]);
    }

    public function updatedAllPermission()
    {
        if ($this->allPermission) {
            $this->selectedPermissions = Permission::pluck('id')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function updateRole()
    {
      $this->validate([
            'name' => 'required|unique:roles,name,' .$this->role->id,
         'data_access_level' => 'required|string',
            'selectedPermissions' => 'required',

        ]);
      $user=auth()->user()->user_code;
      $in=$this->role;
       $updted=$this->role->update([
          'name' => $this->name,
          'data_access_level' => $this->data_access_level,
          'description' => $this->description,
          'updated_by' => $user,
       ]);
       $this->role->permissions()->sync($this->selectedPermissions);
       $this->role = $this->role->fresh();
       if($this->role)
       {
          session()->flash('success', 'Role Updated Successfully.');
          $rand = Str::random(20);
          $activityLog = new activity_log();
          $activityLog->activity = 'Updating role and platform permission';
          $activityLog->user_code = auth()->user()->user_code;
          $activityLog->section = 'Role update';
          $activityLog->action = 'Role '.$this->role->description." was updated by ".$user;
          $activityLog->userID = auth()->user()->id;
          $activityLog->activityID = $rand;
          $activityLog->ip_address = "";
          $activityLog->save();
          return redirect()->route('roles.create');
       }
       session()->flash('Error on Updating role','error');
       return back()->withInput();

    }
}
