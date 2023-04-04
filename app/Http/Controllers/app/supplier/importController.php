<?php

namespace App\Http\Controllers\app\supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\suppliers;
use Maatwebsite\Excel\Facades\Excel;
use SebastianBergmann\Exporter\Exporter;

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
      return view('app.suppliers.import');
   }

   /**
    * store uploaded file
    *
    * @return \Illuminate\Http\Response
    */
   public function import(Request $request)
   {
      $this->validate($request, [
         'upload_import' => 'required'
      ]);
      $file = request()->file('upload_import');

      Excel::import(new suppliers, $file);

      Session()->flash('success', 'Suppliers imported Successfully.');

      return redirect()->route('supplier');
   }


   /**
    * download contacts to excel
    *
    * @return \Illuminate\Http\Response
    */
   public function export()
   {
      return Excel::download(new Exporter, 'suppliers.xlsx');
   }

   /**
    * download sample csv
    *
    * @return \Illuminate\Http\Response
    */
   public function download_import_sample()
   {

      $file = public_path() . "/samples/supplier_import_sample_file.csv";

      return response()->download($file);
   }
}
