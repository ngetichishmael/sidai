<?php

namespace App\Exports;

use App\Models\customers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromView, WithMapping
{
   private $filteredCustomers;

   public function __construct($filteredCustomers)
   {
      $this->filteredCustomers = $filteredCustomers;
   }

   public function map($customer): array
   {
      // You can use the filtered data here if needed
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

   public function view(): View
   {
      // Use $this->filteredCustomers instead of querying the database
      $customers = $this->filteredCustomers;

      return view('Exports.employees', [
         'contacts' => $customers,
      ]);
   }
}




//class CustomersExport implements FromView, WithMapping
//{
//   public function map($customer): array
//   {
//      return [
//         $customer->customer_name,
//         $customer->phone_number,
//         $customer->address,
//         $customer->creator->name,
//         optional($customer->Area->Subregion->Region)->name,
//         optional($customer->Area->Subregion)->name,
//         optional($customer->Area)->name,
//         optional($customer->Creator)->name,
//         optional($customer)->created_at,
//      ];
//   }
//
////   protected $timeInterval;
////
////   public function __construct($timeInterval = null)
////   {
////      $this->timeInterval = $timeInterval;
////   }
//
//   public function view(): View
//   {
//      $query = customers::orderBy('id', 'DESC');
//
//      $customers = $query->get();
//
//      return view('Exports.customers', [
//         'contacts' => $customers,
//      ]);
//   }
//}
