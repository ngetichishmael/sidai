<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitForm;
use App\Models\VisitFormSubmission;
use App\Services\DynamicFormValidator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class VisitFormSubmissionController extends Controller
{
   public function store(Request $request)
   {
      try {
         $validatedData = $request->validate([
            'form_type' => 'required|string',
            'region' => 'required|string',
            'location' => 'required|string',
            'form_data' => 'required|array',
            'form_data.visit_form_id' => 'required|string',
            'form_data.fields' => 'required|array',
            'form_data.fields.*.name' => 'required|string',
            'form_data.fields.*.type' => 'required|string|in:text,number,date,image',
            'form_data.fields.*.answer' => 'required_if:form_data.fields.*.type,image|string', // Only required for image fields
         ]);

         // Extract the data from the request
         $formData = $validatedData['form_data'];
         $formType = $validatedData['form_type'];
         $region = $validatedData['region'];
         $location = $validatedData['location'];

         foreach ($formData['fields'] as &$field) {
            if ($field['type'] === 'image' && isset($field['answer']) && $field['answer'] instanceof UploadedFile) {
               $imageUrl = $field['answer']->store('images', 'public');
               $field['answer'] = asset('storage/' . $imageUrl);
            }
         }
         $formDataJson = json_encode($formData);

         // Create the VisitFormSubmission
         $formSubmission = VisitFormSubmission::create([
            'form_type' => $formType,
            'region' => $region,
            'location' => $location,
            'form_data' => $formDataJson,
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'staff_name' => auth()->user()->name, // Assuming you have a user model with 'name' attribute
         ]);

         return response()->json(['message' => 'Form submitted successfully'], 201);
      } catch (ValidationException $e) {
         return response()->json(['errors' => $e->errors()], 422);
      }
   }


   public function store2(Request $request, DynamicFormValidator $validator)
   {
      try {
         $formDataArray = $request->input('form_data');
         $visitFormId = $formDataArray['visit_form_id'];
         $form = VisitForm::findOrFail($visitFormId);

         $validatedData = $validator->validate($request->all(), [
            'form_type' => 'required|string',
            'region' => 'required|string',
            'location' => 'required|string',
            'form_data' => 'required|array',
            'form_data.visit_form_id' => 'required|integer',
            'form_data.fields' => 'required|array',
            'form_data.fields.*.name' => 'required|string',
            'form_data.fields.*.type' => 'required|string|in:text,number,date,image',
            'form_data.fields.*.answer' => 'required_if:form_data.fields.*.type,image|string', // Only required for image fields
         ]);

         // Extract the data from the request
         $formData = $validatedData['form_data'];
         $formType = $validatedData['form_type'];
         $region = $validatedData['region'];
         $location = $validatedData['location'];

         foreach ($formData['fields'] as &$field) {
            if ($field['type'] === 'image' && isset($field['answer']) && $field['answer'] instanceof UploadedFile) {
               $imageUrl = $field['answer']->store('images', 'public');
               $field['answer'] = asset('storage/' . $imageUrl);
            }
         }

         // Create the VisitFormSubmission
         $formSubmission = VisitFormSubmission::create([
            'form_type' => $formType,
            'region' => $region,
            'location' => $location,
            'form_data' => $formData,
            'user_id' => auth()->id(), // Assuming you have authentication set up
            'staff_name' => $request->user()->name, // Assuming you have a user model with 'name' attribute
         ]);

         return response()->json(['message' => 'Form submitted successfully'], 201);
      } catch (ValidationException $e) {
         return response()->json(['errors' => $e->errors()], 422);
      }
   }


   public function store1(Request $request, DynamicFormValidator $validator)
   {

      $formId = $request->input('form_id');
      $form = VisitForm::findOrFail($formId);

      $validatedData = $validator->validate($form->fields, $request->all());

      if (is_array($validatedData)) {
         $formSubmission = VisitFormSubmission::create([
            'form_type' => $form->name,
            'user_id' => auth()->id(),
            'staff_name' => $request->user()->name,
            'region' => $request->region,
            'location' => $request->location,
            'form_data' => $validatedData,
         ]);

         return response()->json(['message' => 'Form submitted successfully'], 201);
      }

      return $validatedData;
   }
}
