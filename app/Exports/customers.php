<?php

namespace App\Exports;

use App\Models\finance\customer\customers as customer;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class customers implements FromCollection, WithHeadings
{
   /**
 * @return \Illuminate\Support\Collection
   */
   public function collection()
   {
      return customer::join('customer_address','customer_address.customerID','=','customers.id')
                  ->where('businessID', Auth::user()->businessID)
                  ->select('contact_type','designation','department','salutation','customer_name','email','email_cc','other_phone_number','primary_phone_number','website','facebook','twitter','linkedin','bill_attention','bill_street','bill_city','bill_state','bill_postal_address','bill_zip_code','ship_attention','ship_street','ship_city','ship_state','ship_zip_code','ship_postal_address')
                  ->get();
   }

   public function headings(): array
   {
      return [
         'contact type',
         'Designation',
         'Department',
         'Salutation',
         'Customer name',
         'Email',
         'Email cc',
         'Other phone number',
         'Primary phone number',
         'Website',
         'facebook',
         'Twitter',
         'Linkedin',
         'Bill attention',
         'Bill street',
         'Bill city',
         'Bill state',
         'Bill postal address',
         'Bill zip code',
         'Ship attention',
         'Ship street',
         'Ship city',
         'Ship state',
         'Ship zip code',
         'Ship postal address',
      ];
   }
}
