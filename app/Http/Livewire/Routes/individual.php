<?php

namespace App\Http\Livewire\Routes;

use App\Models\Routes;
use Livewire\Component;
use Livewire\WithPagination;

class individual extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';
    public function render()
    {
        $routes = Routes::with("username")->where('Type', 'Individual')
        ->orderBy('created_at', 'desc')
        ->paginate($this->perPage);
        

        return view('livewire.routes.individual', compact('routes'));
    }
}
