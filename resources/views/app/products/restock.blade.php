@extends('layouts.app')
{{-- page header --}}
@section('title', 'Restock Product')


{{-- content section --}}
@section('content')
    <!-- begin page-header -->
    <style>
       /* CSS styles */
       table {
          width: 100%;
          border-collapse: collapse;
       }

       th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd;
       }

       .sku-field input {
          width: 100%;
          padding: 8px;
          box-sizing: border-box;
       }

       .sku-field .remove-sku {
          color: #fff;
          background-color: #f44336;
          border: none;
          padding: 8px 12px;
          cursor: pointer;
       }

       .sku-field .remove-sku:hover {
          background-color: #d32f2f;
       }
    </style>
    <h3 class="page-header"> Restock <b>{{$product_information->product_name ?? ''}}</b> Products </h3>
    <!-- end page-header -->
    <form class="needs-validation responsive mt-3 mb-3" action="{{ route('products.updatestock', [
        'id' => $id]) }}" method="POST"
          enctype="multipart/form-data" id="restock-form" id="myForm">
       @csrf
       <table id="sku-table responsive">
          <thead>
          <tr>
             <th>Product SKU Code</th>
             <th>Current Quantity</th>
             <th>Restock Quantity </th>
          </tr>
          </thead>
          <tbody id="sku-fields">
          <tr class="sku-field ">
             <td><input for="fp-date-time" type="text" class="form-control col-lg-1 col-md-3" name="sku_codes[]" value="{{$product_information->sku_code}}"  readonly required></td>
             <td><input for="fp-date-time" type="number" class="form-control col-lg-1 col-md-3" value="{{$product_information->inventory->current_stock}}" readonly ></td>
             <td><input for="fp-date-time" pattern="^[1-9]\d*$" type="number" class="form-control col-lg-1 col-md-3" id="quantityInput" name="quantities[]" required></td>
          </tr>
          </tbody>
       </table>
       <div class=" col-l-1 mt-3 pe-4 text-right">
          <button wire:click.prevent="submit()" type="submit"
                  class="btn btn-primary data-submit w-10">Submit</button>
       </div>
    </form>

    <script>
       // Add SKU field
       document.getElementById('add-sku').addEventListener('click', function() {
          var skuFields = document.getElementById('sku-fields');
          var newField = document.createElement('tr');
          newField.classList.add('sku-field');
          newField.innerHTML = `
            <td><input for="fp-date-time" type="text" class="form-control col-lg-3 col-md-6" name="sku_codes[]" required></td>
             <td><input for="fp-date-time" type="number" class="form-control col-lg-1 col-md-3" name="quantities[]" required></td>
             <td><button for="fp-date-time"  type="button" class="remove-sku form-control btn btn-sm btn-outline-danger" style="width: fit-content">
                    <i class="fas fa-trash mr-25"></i><span> &nbsp;Delete</span></button>
             </td>
        `;
          skuFields.appendChild(newField);
       });

       // Remove SKU field
       document.addEventListener('click', function(event) {
          if (event.target.classList.contains('remove-sku')) {
             var skuField = event.target.closest('.sku-field');
             skuField.parentNode.removeChild(skuField);
          }
       });

       // const quantityInput = document.getElementById("quantityInput");
       // quantityInput.addEventListener("input", function () {
       //    const inputValue = parseFloat(quantityInput.value);
       //    if (inputValue < 0 || !Number.isInteger(inputValue)) {
       //       quantityInput.value = 0;
       //    }
       // });


       // const form = document.getElementById('myForm');
       // const inputElement = document.getElementById('quantityInput');
       //
       //
       // form.addEventListener('submit', function (event) {
       //    const value = parseFloat(inputElement.value);
       //
       //    if (isNaN(value) || value < 1 || !Number.isInteger(value)) {
       //       event.preventDefault();
       //       alert('Please enter a valid whole number greater than or equal to 1.');
       //    }
       // });



       function validate() {
          var input = document.getElementById("quantityInput")[0];
          var value = input.value;
          console.log(value);
          if (value < 1 || !Number.isInteger(Number(value))) {
             alert("Please enter a positive number");
             return false;
          }
          return;
       }
    </script>
@endsection
{{-- page scripts --}}
@section('scripts')
@endsection
