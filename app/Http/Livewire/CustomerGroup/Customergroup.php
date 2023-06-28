<?php

namespace App\Http\Livewire\CustomerGroup;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customer_group;

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
