<?php

namespace App\Http\Controllers\app\supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\suppliers\suppliers;
use App\Models\suppliers\category;
use App\Models\country;
use Illuminate\Support\Facades\Auth;

class supplierController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
   }

   public function index()
   {
      return view('app.suppliers.index');
   }
   public function show()
   {
      return view('app.suppliers.index');
   }

   public function create()
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id')->prepend('Choose Country', '');
      $groups = category::OrderBy('id', 'DESC')->pluck('name', 'id');
      return view('app.suppliers.create', compact('country', 'groups'));
   }

   public function store(Request $request)
   {
      $this->validate($request, [
         'email' => 'required',
         'phone_number' => 'required',
      ]);

      $primary = new suppliers;
      $primary->email = $request->email;
      $primary->name = $request->name;
      $primary->phone_number = $request->phone_number;
      $primary->telephone = $request->telephone;
      $primary->status = "Active";
      $primary->business_code = Auth::user()->business_code;
      $primary->save();

      Session()->flash('success', 'Supplier has been successfully Added');

      return redirect()->route('supplier.index');
   }

   public function edit($id)
   {
      $country = country::OrderBy('id', 'DESC')->pluck('name', 'id')->prepend('Choose Country', '');
      $suppliers = suppliers::where('suppliers.id', $id)
         ->first();
      //category
      $category = category::where('business_code', Auth::user()->business_code)->get();

      return view('app.suppliers.edit', compact('category', 'suppliers', 'country'));
   }

   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'email' => 'required',
         'phone_number' => 'required',
      ]);

      $edit = suppliers::where('id', $id)->first();
      $edit->email = $request->email;
      $edit->name = $request->name;
      $edit->phone_number = $request->phone_number;
      $edit->telephone = $request->telephone;
      $edit->status = $request->status;
      $edit->business_code = Auth::user()->business_code;
      $edit->save();

      Session()->flash('success', 'Supplier has been successfully updated');

      return redirect()->back();
   }

   //delete permanently
   public function delete($id)
   {
   }
}
