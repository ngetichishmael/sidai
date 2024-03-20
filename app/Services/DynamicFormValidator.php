<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class DynamicFormValidator
{
   public function validate($formFields, $formData)
   {
      $rules = $this->generateValidationRules($formFields);
      $validator = Validator::make($formData, $rules);

      if ($validator->fails()) {
         // Handle validation errors
         return response()->json(['errors' => $validator->errors()], 422);
      }

      return $validator->validated();
   }

   private function generateValidationRules($formFields)
   {
      $rules = [];
      foreach ($formFields as $field) {
         $fieldRules = [];
         if ($field['required']) {
            $fieldRules[] = 'required';
         }

         switch ($field['type']) {
            case 'text':
               $fieldRules[] = 'string';
               break;
            case 'number':
               $fieldRules[] = 'numeric';
               break;
            case 'date':
               $fieldRules[] = 'date';
               break;
            case 'image':
               $fieldRules[] = 'nullable';
               $fieldRules[] = 'image';
               break;
         }

         $rules[$field['name']] = implode('|', $fieldRules);
      }

      return $rules;
   }
}
