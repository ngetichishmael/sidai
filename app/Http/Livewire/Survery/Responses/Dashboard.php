<?php

namespace App\Http\Livewire\Survery\Responses;

use App\Exports\ResponsesExport;
use App\Models\SurveyResponses;
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
      $reponses = SurveyResponses::whereLike(
         [
            'Customer.customer_name',
            'Survey.description',
            'Answer',
            'reason'
         ],
         $searchTerm
      )
         ->paginate($this->perPage);
      return view('livewire.survery.responses.dashboard', [
         'responses' => $reponses
      ]);
   }
   public function export()
   {
      return Excel::download(new ResponsesExport, 'responses.xlsx');
   }
}
