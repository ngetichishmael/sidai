<?php

namespace App\Http\Livewire\Customers;

use App\Exports\customers as ExportsCustomers;
use App\Models\customers;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $contacts = customers::with('Area.Subregion.Region', 'Creator')
         ->search($searchTerm)
         ->orderBy('id', 'DESC')
         ->paginate($this->perPage);
      return view('livewire.customers.dashboard', [
         'contacts' => $contacts
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
}
