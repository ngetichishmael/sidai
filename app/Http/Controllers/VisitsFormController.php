<?php

namespace App\Http\Controllers;

use App\Models\VisitForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitsFormController extends Controller
{
   public function index()
   {
      $forms = VisitForm::all();
      return view('app.ReportForms.form-builder', compact('forms'));
   }

   public function store(Request $request)
   {
//      $validatedData = $request->validate([
//         'name' => 'required',
//         'description' => 'nullable',
//         'fields' => 'required|array',
//         'fields.*' => 'array',
//         'fields.*.*' => 'required_with:fields.*.*',
//         'fields.*.type' => 'in:text,number,date', // Add more field types as needed
//         'fields.*.required' => 'nullable|boolean',
//      ]);
//      $forms = VisitForm::create($validatedData);

      $validator = Validator::make($request->all(), [
         'name' => 'required',
         'description' => 'nullable',
         'type' => 'required',
         'fields' => 'required|array',
         'fields.*.name' => 'required',
         'fields.*.type' => 'required|in:text,number,date,image', // Add more field types as needed
         'fields.*.required' => 'nullable|boolean',
      ]);

      // Check if validation fails
      if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
      }

      // If validation passes, create the VisitForm record
      $formData = $validator->validated(); // Get the validated data

      // Create the VisitForm record
      $forms = VisitForm::create([
         'name' => $formData['name'],
         'description' => $formData['description'],
         'fields' => $formData['fields'],
      ]);

      return redirect()->route('forms', compact('forms'))->with('success', 'Form created successfully');
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
