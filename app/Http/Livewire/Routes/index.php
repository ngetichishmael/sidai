<?php

namespace App\Http\Livewire\Routes;

use App\Models\Routes;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $search = '';
   public function render()
   {
<<<<<<< HEAD
<<<<<<< HEAD
      $routes = Routes::with("user")->where('Type','Assigned')
      ->orderBy('created_at', 'desc')->paginate($this->perPage);
    //     $routes = Routes::with("user")->where('Type','Assigned')->paginate($this->perPage);
=======
      $routes = Routes::with("user")->where('Type','Assigned')->paginate($this->perPage);


>>>>>>> 2c6f24817e5c9256a837d530086a3f0b97431fdf
=======
      $routes = Routes::with("user")->where('Type','Assigned')
      ->orderBy('created_at', 'desc')->paginate($this->perPage);
    //     $routes = Routes::with("user")->where('Type','Assigned')->paginate($this->perPage);
>>>>>>> 8c509ae0f46186cc10097e581041580d5697af5e
      return view('livewire.routes.index', compact('routes'));
   }
}
