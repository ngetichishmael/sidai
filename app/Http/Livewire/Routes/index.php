<?php

namespace App\Http\Livewire\Routes;

use Livewire\Component;
use App\Models\customer\customers;
use Livewire\WithPagination;
use App\Models\Routes;
use Auth;
class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $search = '';
   public function render()
   {
      $routes = Routes::with("user")->where('Type','Assigned')
      ->orderBy('created_at', 'desc')->paginate($this->perPage);
      

      return view('livewire.routes.index', compact('routes'));
   }
}
