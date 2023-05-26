<?php

namespace App\Http\Livewire\PriceGroup;

use App\Models\PriceGroup;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 40;
    public $sortField = 'id';
    public $sortAsc = true;
    public function render()
    {
        $groups = PriceGroup::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.price-group.dashboard', [
            'groups' => $groups
        ]);
    }
}
