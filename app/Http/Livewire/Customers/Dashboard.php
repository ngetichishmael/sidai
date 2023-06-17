<?php

namespace App\Http\Livewire\Customers;

use App\Exports\customers as ExportsCustomers;
use App\Models\customers;
use Livewire\Component;
use App\Models\Region;
use App\Models\customer_group;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
   use WithPagination;
   public $region = null;
   public $group = null;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $contacts = customers::search($searchTerm)
         ->where('customer_type', 'LIKE', 'normal')
         ->orderBy('id', 'DESC')
         ->paginate($this->perPage);
      return view('livewire.customers.dashboard', [
         'contacts' => $contacts,
         'regions' =>$this->region(),
         'groups' =>$this->groups()
      ]);
   }
   public function export()
   {
      return Excel::download(new ExportsCustomers, 'customers.xlsx');
   }
   public function deactivate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "Suspended"]
      );
      return redirect()->to('/customer');
   }
   public function activate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "Approved"]
      );

      return redirect()->to('/customer');
   }
   public function region(){
      $region = Region::all();
      return $region;
   }
   public function groups(){
      $groups = customer_group::all();
         return $groups;
   }
   
}
