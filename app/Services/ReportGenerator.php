<?php

namespace App\Services;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\VisitForm;
use App\Models\VisitFormSubmission;

class ReportGenerator
{
   public function generateReport($formType, $filters = [])
   {
      $form = VisitForm::where('name', $formType)->firstOrFail();

      $formSubmissions = VisitFormSubmission::where('form_type', $formType);

      // Apply filters (e.g., date range, user, region)
      foreach ($filters as $key => $value) {
         $formSubmissions->where($key, $value);
      }

      $formSubmissions = $formSubmissions->get();

      // Generate report based on the form data
      $report = [];
      foreach ($formSubmissions as $submission) {
         $report[] = $this->processSubmission($submission, $form->fields);
      }

      return $report;
   }

   private function processSubmission($submission, $formFields)
   {
      $report = [
         'form_type' => $submission->form_type,
         'user_id' => $submission->user_id,
         'staff_name' => $submission->staff_name,
         'region' => $submission->region,
         'form_data' => [],
      ];

      foreach ($formFields as $field) {
         $fieldName = $field['name'];
         $report['form_data'][$fieldName] = $submission->form_data[$fieldName] ?? null;
      }
      return $report;
   }
}
