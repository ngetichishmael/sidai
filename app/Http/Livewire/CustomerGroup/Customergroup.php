<?php

namespace App\Http\Livewire\CustomerGroup;

use App\Models\customer_group;
use Livewire\Component;
use Livewire\WithPagination;

class Customergroup extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 40;
    public $sortField = 'id';
    public $sortAsc = true;
    public function render()
    {$groups = customer_group::orderBy($this->sortField, $this->sortAsc ? 'desc' : 'asc')
        ->paginate($this->perPage);
        return view('livewire.customer-group.dashboard', [
            'groups' => $groups
        ]);
    }
}
