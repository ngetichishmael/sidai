<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityExport implements FromView, WithMapping
{
   private $filteredLogs;

   public function __construct($filteredLogs)
{
   $this->filteredLogs = $filteredLogs;
}

   public function map($log): array
{
   // You can use the filtered data here if needed
   return [
      $log->activity,
      optional($log->user)->name,
      $log->section,
      $log->action,
      optional($log)->created_at,
   ];
}

   public function view(): View
{
   // Use $this->filteredlogs instead of querying the database
   $logs = $this->filteredLogs;

   return view('Exports.activity', [
      'activities' => $logs,
   ]);
}
}
