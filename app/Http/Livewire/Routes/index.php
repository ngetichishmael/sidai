<?php

namespace App\Http\Livewire\Routes;

use App\Models\Routes;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search = '';

    public function render()
    {

        $routes = Routes::with("user")->whereHas('user')->where('Type', 'Assigned')
        ->when($this->search, function ($query) {
            $query->where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($this->search) . '%');
        })
            ->orderBy('created_at', 'desc')->paginate($this->perPage);
          
        return view('livewire.routes.index', compact('routes'));
    }
}
