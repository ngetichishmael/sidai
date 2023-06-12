@extends('layouts.app')

@section('title', 'Assign Shop Attendee')

@section('content')


{{--   <div>--}}
{{--      <div class="col-lg-12 col-12">--}}
{{--         <div class="card">--}}
{{--            <h5 class="card-header">Assign Target</h5>--}}
{{--         </div>--}}
{{--         <div class="card">--}}
{{--            <div class="card-body p-0">--}}
{{--               <div>--}}
{{--                  <table class="table">--}}
{{--                     <thead class="thead-light">--}}
{{--                     <tr>--}}
{{--                        <th>Sales Force</th>--}}
{{--                        <th>Action</th>--}}
{{--                     </tr>--}}
{{--                     </thead>--}}
{{--                     <tbody>--}}
{{--                     @foreach ($warehouse as $index => $target)--}}
{{--                        <tr class="col-8">--}}
{{--                           <td>--}}
{{--                              <label for="fp-date-time">Shop Attendee</label>--}}
{{--                              <select wire:model="warehouse.{{ $index }}.user_code"--}}
{{--                                      class="form-control @error('warehouse.'.$index.'.user_code') border border-danger @enderror">--}}
{{--                                 <option value="">-- choose Shop Attendee --</option>--}}
{{--                                 @foreach ($shopattendee as $user)--}}
{{--                                    <option value="{{ $user->user_code }}">{{ $user->name }}</option>--}}
{{--                                 @endforeach--}}
{{--                              </select>--}}
{{--                              @error('warehouse.'.$index.'.user_code')--}}
{{--                              <span class="error">{{ $message }}</span>--}}
{{--                              @enderror--}}
{{--                           </td>--}}
{{--                           <td>--}}
{{--                              <a type="button" class="btn btn-outline-danger" href="#"--}}
{{--                                 wire:click="removeTargets({{ $index }})">--}}
{{--                                 <i data-feather="trash-2" class="mr-25"></i>--}}
{{--                                 <span>Delete</span>--}}
{{--                              </a>--}}
{{--                           </td>--}}
{{--                        </tr>--}}
{{--                     @endforeach--}}
{{--                     </tbody>--}}
{{--                  </table>--}}
{{--                  <div class="row">--}}
{{--                     <div class="col-md-12 m-2">--}}
{{--                        <button wire:click.prevent="addTargets" type="button" class="btn btn-outline-primary">--}}
{{--                           <i data-feather="user-plus" class="mr-25"></i>--}}
{{--                           <span>Add New Row</span>--}}
{{--                        </button>--}}
{{--                     </div>--}}
{{--                  </div>--}}
{{--               </div>--}}

{{--               @error('warehouse')--}}
{{--               <span class="error">{{ $message }}</span>--}}
{{--               @enderror--}}
{{--               <div class="m-2">--}}
{{--                  <button wire:click.prevent="submit()" type="submit"--}}
{{--                          class="btn btn-primary mr-1 data-submit">Submit</button>--}}
{{--               </div>--}}
{{--            </div>--}}
{{--         </div>--}}
{{--      </div>--}}
{{--   </div>--}}

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
<div class="row mb-2">
   <div class="col-md-8">
      <h2 class="page-header"><i data-feather="list"></i> Assign Shop Attendee</h2>
   </div>
</div>

@include('partials._messages')
   <!-- end page-header -->
   <form class="needs-validation responsive" action="{{ route('warehousing.assign', [
        'code' => $code]) }}" method="POST"
         enctype="multipart/form-data" id="restock-form">
      @csrf
      <table id="sku-table responsive">
         <thead class="thead-light">
         <tr>
            <th>Sales Force</th>
            <th>Action</th>
         </tr>
         </thead>
         <tbody id="sku-fields">
         <tr class="sku-field ">

            <td>
               <select class="form-control"
                        class="form-control border border-danger">
                  <option value="">-- choose Shop Attendee --</option>
                  @foreach ($shopattendee as $user)
                     <option value="{{ $user->user_code }}">{{ $user->name }}</option>
                  @endforeach
               </select></td>
            <td><input for="fp-date-time" type="number" class="form-control col-lg-3 col-md-6" name="quantities[]" required></td>
            <td><button for="fp-date-time"  type="button" class="remove-sku form-control btn btn-sm btn-outline-danger" style="width: fit-content">
                  <i data-feather="trash-2" class="mr-25"></i><span> &nbsp;Delete</span></button>
            </td>
         </tr>
         </tbody>
      </table>
      <div class="row">
         <div class="col-md-12 m-2">
            <button wire:click.prevent="addTargets" type="button" id="add-sku" class="btn btn-outline-primary">
               <i data-feather="plus" class="mr-25 font-medium bold"></i>
               <span>Add New Row</span>
            </button>
         </div>
      </div>
      </div>
      <div class="m-2">
         <button wire:click.prevent="submit()" type="submit"
                 class="btn btn-primary mr-1 data-submit">Submit</button>
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
   </script>

@endsection
