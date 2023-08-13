<?php

namespace App\Http\Livewire\Routes;

use App\Models\Routes;
use Auth;
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
      $routes = Routes::with("user")->where('Type','Individual')->paginate($this->perPage);

      return view('livewire.routes.individual', compact('routes'));
   }
}
