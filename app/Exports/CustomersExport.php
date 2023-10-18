<?php

namespace App\Exports;

use App\Models\customers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromView, WithMapping
{
   public function map($customer): array
   {
      return [
         $customer->customer_name,
         $customer->phone_number,
         $customer->address,
         $customer->creator->name,
         optional($customer->Area->Subregion->Region)->name,
         optional($customer->Area->Subregion)->name,
         optional($customer->Area)->name,
         optional($customer->Creator)->name,
         optional($customer)->created_at,
      ];
   }

//   protected $timeInterval;
//
//   public function __construct($timeInterval = null)
//   {
//      $this->timeInterval = $timeInterval;
//   }

   public function view(): View
   {
      $query = customers::orderBy('id', 'DESC');

      $customers = $query->get();

      return view('Exports.customers', [
         'contacts' => $customers,
      ]);
   }
}
