<?php

namespace App\Http\Livewire\Support;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $search = '';
   public $orderBy = 'id';
   public $orderAsc = true;
    public function render()
    {
       $unreadCount = SupportTicket::where('read', 0)->count();
       $tickets = SupportTicket::with('customer','user')->orderBy($this->orderBy,$this->orderAsc ? 'desc' : 'asc')
          ->paginate($this->perPage);
       return view('livewire.support.index', compact('tickets','unreadCount'));
    }
}
