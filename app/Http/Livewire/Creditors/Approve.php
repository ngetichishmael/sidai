<?php

namespace App\Http\Livewire\Creditors;

use App\Exports\customers as ExportsCustomers;
use App\Models\customer_group;
use App\Models\customers;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Approve extends Component
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
       $contacts = customers::where('is_creditor', 1)->where('creditor_status', 'waiting_approval')
           ->with('Area.Subregion.Region', 'Creator')
          ->search($searchTerm)
          ->orderBy('id', 'DESC')
          ->paginate($this->perPage);
       return view('livewire.creditors.approve', [
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
   public function approveCreditor($id)
   {
      customers::whereId($id)->update(
         ['creditor_status' => "approved",
            'customer_type' => 'creditor',
            'is_creditor' => 1
         ]
      );
      return redirect()->to('/approveCreditors');
   }
   public function dissaproveCreditor($id)
   {
      customers::whereId($id)->update(
         ['creditor_status' => "disapproved",
            'customer_type' => 'creditor',
            'is_creditor' => 1
         ]
      );
      return redirect()->to('/approveCreditors');
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
