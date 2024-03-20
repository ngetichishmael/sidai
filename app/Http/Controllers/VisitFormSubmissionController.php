<?php

namespace App\Http\Controllers;

use App\Models\VisitForm;
use App\Models\VisitFormSubmission;
use App\Services\DynamicFormValidator;
use Illuminate\Http\Request;

class VisitFormSubmissionController extends Controller
{
   public function store(Request $request, DynamicFormValidator $validator)
   {
      $formId = $request->input('form_id');
      $form = VisitForm::findOrFail($formId);

      $validatedData = $validator->validate($form->fields, $request->all());

      if (is_array($validatedData)) {
         $formSubmission = VisitFormSubmission::create([
            'form_type' => $form->name,
            'user_id' => auth()->id(),
            'staff_name' => $request->staff_name,
            'region' => $request->region,
            'form_data' => $validatedData,
         ]);

         return response()->json(['message' => 'Form submitted successfully'], 201);
      }

      return $validatedData;
   }
}
