<?php

namespace App\Exports;

use App\Models\customer\checkin;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerVisitExport implements FromView
{

     /**
     * @return \Illuminate\Support\FromView
     */

     protected $timeInterval;

     public function __construct($timeInterval = null)
     {
         $this->timeInterval = $timeInterval;
     }
 
 
     public function view(): View
     {
         $query = checkin::select('customer_checkin.*', 'users.name')
                        ->join('users', 'customer_checkin.user_code', '=', 'users.user_code')
                        ->orderBy('customer_checkin.user_code') // Order by user_code (users' codes) in ascending order
                        ->orderBy('users.name'); // Order users' names in alphabetical order
 
         if ($this->timeInterval === 'today') {
             $query->whereDate('customer_checkin.created_at', today());
         } elseif ($this->timeInterval === 'yesterday') {
             $query->whereDate('customer_checkin.created_at', today()->subDay());
         } elseif ($this->timeInterval === 'this_week') {
             $query->whereBetween('customer_checkin.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
         } elseif ($this->timeInterval === 'this_month') {
             $query->whereYear('customer_checkin.created_at', now()->year)->whereMonth('checkins.created_at', now()->month);
         } elseif ($this->timeInterval === 'this_year') {
             $query->whereYear('customer_checkin.created_at', now()->year);
         }
 
         $checkin = $query->get();
 
         return view('Exports.visit', [
             'visits' => $checkin,
         ]);
     }

}
