<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class RolesManagement extends Component
{
    public $name;
    public $selectedPermissions = [];
    public $allPermission = false;
    public function render()
    {
        $data = Permission::get();

        return view('livewire.role.roles-management', [
            'permissions' => $data,
        ]);
    }

    public function updatedAllPermission()
    {
        if ($this->allPermission) {
            $this->selectedPermissions = Permission::pluck('name')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }
    public function storeRole()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'selectedPermissions' => 'required',
        ]);

        $role = Role::create(['name' => strtolower(trim($this->name))]);
        $role->syncPermissions($this->selectedPermissions);

        if ($role) {
            session()->flash('success', 'New Role Added Successfully.');
            return redirect()->route('role.index');
        }

        session()->flash('error', 'Error on Saving role');
    }
}
