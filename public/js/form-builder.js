document.addEventListener('DOMContentLoaded', function () {
   const formBuilderForm = document.getElementById('form-builder-form');
   const formFieldsContainer = document.getElementById('form-fields');
   const addFieldButton = document.getElementById('add-field-btn');
   let fieldIndex = 0;
   addFieldButton.addEventListener('click', addField);


   function addField() {
      const fieldContainer = document.createElement('div');
      fieldContainer.classList.add('form-group', 'mb-3'); // Add margin bottom for space

      const fieldInput = document.createElement('input');
      fieldInput.type = 'text';
      fieldInput.classList.add('form-control');
      fieldInput.style.marginTop = '-1px';
      fieldInput.name = `fields[${fieldIndex}][name]`;
      fieldInput.placeholder = 'Field Name'; // Placeholder text for input

      const fieldTypeSelect = document.createElement('select');
      fieldTypeSelect.classList.add('form-control');
      fieldTypeSelect.style.marginTop = '-1px';
      fieldTypeSelect.name = `fields[${fieldIndex}][type]`;

      const fieldTypeOptions = [
         { value: 'text', label: 'Text' },
         { value: 'number', label: 'Number' },
         { value: 'date', label: 'Date' },
         { value: 'image', label: 'Image' },
      ];

      fieldTypeOptions.forEach(option => {
         const optionElement = document.createElement('option');
         optionElement.value = option.value;
         optionElement.textContent = option.label;
         fieldTypeSelect.appendChild(optionElement);
      });

      const requiredLabel = document.createElement('label');
      requiredLabel.textContent = 'Required'; // Label for the checkbox

      const fieldRequiredHidden = document.createElement('input');
      fieldRequiredHidden.type = 'hidden';
      fieldRequiredHidden.name = `fields[${fieldIndex}][required]`;
      fieldRequiredHidden.value = '0'; // Default value for 'required' when checkbox is unchecked

      const fieldRequired = document.createElement('input');
      fieldRequired.type = 'checkbox';
      fieldRequired.style.marginTop = '-1px';
      fieldRequired.name = `fields[${fieldIndex}][required]`;
      fieldRequired.value = 1; // Set the value to 1 for checked state
      fieldRequired.addEventListener('change', () => {
         fieldRequiredHidden.value = fieldRequired.checked ? '1' : '0'; // Update hidden input value based on checkbox state
      });

      const removeFieldButton = document.createElement('button');
      removeFieldButton.type = 'button';
      removeFieldButton.classList.add('btn', 'btn-danger', 'ms-2'); // Add margin-left for spacing
      removeFieldButton.textContent = 'Remove Field';
      removeFieldButton.addEventListener('click', () => {
         fieldContainer.remove(); // Remove the field group when the button is clicked
      });

      fieldContainer.appendChild(fieldInput);
      fieldContainer.appendChild(fieldTypeSelect);
      fieldContainer.appendChild(requiredLabel); // Add the label before the checkbox
      fieldContainer.appendChild(fieldRequiredHidden); // Hidden input for 'required' field
      fieldContainer.appendChild(fieldRequired);
      fieldContainer.appendChild(removeFieldButton);

      formFieldsContainer.appendChild(fieldContainer);

      fieldIndex++; // Increment the field index for the next field
   }

   formBuilderForm.addEventListener('submit', function (event) {
      // Format and include dynamically generated field data in the form submission
      const formFieldsData = [];
      formFieldsContainer.querySelectorAll('.form-group').forEach(fieldContainer => {
         const fieldNameInput = fieldContainer.querySelector('input[name^="fields"]');
         const fieldTypeSelect = fieldContainer.querySelector('select[name^="fields"]');
         const fieldRequiredInput = fieldContainer.querySelector('input[name^="fields"]');

         const fieldName = fieldNameInput.value.trim();
         const fieldType = fieldTypeSelect.value;
         const fieldRequired = fieldRequiredInput.value; // Get value from hidden input

         formFieldsData.push({
            name: fieldName,
            type: fieldType,
            required: fieldRequired,
         });
      });

      // Now formFieldsData contains the formatted data in the desired format
      // We will add this to the formData before submitting the form
      const formData = new FormData(formBuilderForm);
      formData.delete('fields'); // Remove any existing 'fields' data
      formFieldsData.forEach(fieldData => {
         formData.append('fields[]', JSON.stringify(fieldData)); // Append each field data to the form data
      });
      // For demonstration, log the form data to the console
      console.log([...formData.entries()]); // Convert FormData to array for logging
   });
});

