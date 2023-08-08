<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class RoleShow extends Component
{
    public $role;
    public function mount(Role $role)
    {
       $this->role = $role->load('CreatedBy','UpdatedBy');
    }

    public function render()
    {
        return view('livewire.role.role-show', [
            'role' => $this->role,
        ]);
    }

    public function getRolesPermissions(Role $role)
    {
        $data = Permission::get();
    }
}
