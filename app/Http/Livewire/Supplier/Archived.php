<?php

namespace App\Http\Livewire\Supplier;

use App\Models\suppliers\suppliers;
use Livewire\Component;

class Archived extends Component
{
use \Livewire\WithPagination;
protected $paginationTheme = 'bootstrap';
public function render()
{
   $suppliers = suppliers::where('status', 'Inactive')
      ->OrderBy('suppliers.id','DESC')
      ->paginate(15);
   return view('livewire.supplier.archived',compact('suppliers'));
}
}
