<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitForm;
use Illuminate\Http\Request;

class VisitsFormController extends Controller
{
   public function index()
   {
      $forms = VisitForm::where('status', 'active')->get();
      return response()->json($forms);
   }

   public function store(Request $request)
   {
      $validatedData = $request->validate([
         'name' => 'required',
         'description' => 'nullable',
         'fields' => 'required|array',
      ]);

      $form = VisitForm::create($validatedData);
      return response()->json($form, 201);
   }

   public function show($id)
   {
      $form = VisitForm::findOrFail($id);
      return response()->json($form);
   }

   public function update(Request $request, $id)
   {
      $validatedData = $request->validate([
         'name' => 'required',
         'description' => 'nullable',
         'fields' => 'required|array',
      ]);

      $form = VisitForm::findOrFail($id);
      $form->update($validatedData);
      return response()->json($form);
   }

   public function destroy($id)
   {
      $form = VisitForm::findOrFail($id);
      $form->delete();
      return response()->json(['message' => 'Form deleted successfully']);
   }
}
