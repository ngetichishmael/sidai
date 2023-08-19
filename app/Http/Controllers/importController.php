<?php

namespace App\Http\Controllers;

use App\Exports\customers as export;
use App\Imports\customers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelData;

class importController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   /**
    * import csv
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('app.customers.import');
   }
   public function show()
   {

      return view('app.customers.import');
   }

   /**
    * store uploaded file
    *
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request, [
         'upload_import' => 'required'
      ]);

      $file = request()->file('upload_import');

      // DataExcel::import(new import, $file);
      Excel::import(new customers, $file);

      Session()->flash('success', 'File imported Successfully.');

      return redirect()->to('/customer');
   }


   /**
    * download contacts to excel
    *
    * @return \Illuminate\Http\Response
    */
   public function export()
   {
      return ExcelData::download(new export, 'customers.xlsx');
   }

   /**
    * download sample csv
    *
    * @return \Illuminate\Http\Response
    */
   public function download_import_sample()
   {
      //PDF file is stored under project/public/download/info.pdf
      $file = public_path() . "/samples/customer_import_sample_file.csv";

      return response()->download($file);
   }
}
