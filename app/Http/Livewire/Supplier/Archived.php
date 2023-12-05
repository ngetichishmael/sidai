<?php

namespace App\Http\Livewire\Supplier;

use App\Models\suppliers\suppliers;
use Livewire\Component;
use Livewire\WithPagination;

class Archived extends Component
{
protected $paginationTheme = 'bootstrap';
   use WithPagination;
   public $perPage = 15;
   public $search = '';
public function render()
{
   $type=auth()->user()->account_type;
   $suppliers = suppliers::where('status', 'Inactive')
      ->where('id', '!=', 1)
      ->OrderBy('suppliers.id','DESC')
      ->paginate(15);
   return view('livewire.supplier.archived',compact('suppliers' ,'type'));
}
}
