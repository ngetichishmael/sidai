<?php

namespace App\Http\Livewire\Users;

use App\Models\Region;
use App\Models\Subregion;
use App\Models\User;
use App\Models\zone;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;

   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $nsm =  User::where('account_type', ['NSM'])->whereLike([
         'Region.name', 'name', 'email', 'phone_number',
      ], $searchTerm)
         ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate($this->perPage);

      return view('livewire.users.index', compact('nsm'));
   }
   public function deactivate($id)
   {
      User::whereId($id)->update(
         ['status' => "Suspended"]
      );
      return redirect()->to('/users');
   }
   public function activate($id)
   {
      User::whereId($id)->update(
         ['status' => "Active"]
      );

      return redirect()->to('/users');
   }
}
