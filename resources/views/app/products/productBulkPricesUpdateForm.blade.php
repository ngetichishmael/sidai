@extends('layouts.app')
{{-- page header --}}
@section('title', 'Product Prices Updates')

{{-- content section --}}
@section('content')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card">
               <div class="card-header">{{ __('Bulk Price Update') }}</div>

               <div class="card-body">
                  @if(session('success'))
                     <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                     </div>
                  @endif
                     <div class="alert alert-info" role="alert">
                        <strong>Instructions:</strong>
                        <ul>
                           <li>Prepare an Excel file with the following columns: <strong>product sku</strong>,<strong>distributor</strong>, <strong>wholesale</strong>, <strong>retail</strong> prices respectively</li>
                           <li>Ensure the file format is either <strong>.xls</strong> or <strong>.xlsx</strong>.</li>
                           <li>Fill in the values for each product accordingly.</li>
                           <li>Upload</li>
                        </ul>
                     </div>
                     <form id="uploadForm" method="post" action="{{ route('products.bulkUpdate', ['warehouse'=>$warehouse]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                           <label for="excel_file">Excel File:</label>
                           <input type="file" name="excel_file" id="excel_file" class="form-control-file" accept=".xls, .xlsx" required>
                           <span id="error" style="color: orangered"></span>
                        </div>
                        <br/>
                        <a href="" class="btn btn-secondary pl-4"> <i class="fa fa-backward"></i>Back</a>
                        <button type="button" onclick="uploadFile()" style="background: rgba(248,72,72,0.7)" class="btn btn-primary pr-5" id="uploadButton" disabled> <i class="fa fa-sync"></i>Update Prices</button>
                        <div class="progress mt-3">
                           <div class="progress-bar bg-info text-dark" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span id="success" class="pl-20" style="color: #5ef65e"></span>
                     </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <script>
      function uploadFile() {
         let form = document.getElementById('uploadForm');
         let succes = $('#success');
         let formData = new FormData(form);

         let progressBar = $('#progressBar');
         let erro = $('#error');
         erro.text("");
         succes.text("");
         progressBar.css('0%');
         progressBar.attr('aria-valuenow', 0);
         progressBar.text(0 + '%');

         $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
               let xhr = new window.XMLHttpRequest();
               xhr.upload.addEventListener('progress', function (e) {
                  if (e.lengthComputable) {
                     let percent = Math.round((e.loaded / e.total) * 100);
                     progressBar.css('width', percent + '%');
                     progressBar.attr('aria-valuenow', percent);
                     progressBar.text(percent + '%');
                  }
               });
               return xhr;
            },
            success: function (response) {
               var successMessage = 'Upload Complete \n ';
               // if (response.success_messages) {
               //    for (let message of response.success_messages) {
               //       successMessage += message + ' \n ';
               //    }
               // }
               succes.text('Prices successfully Updated, Now redirecting Back...');
               setTimeout(function () {
                  if (response.redirect) {
                     window.location.href = response.redirect;
                  }
               }, 3000);
            },
            error: function (xhr, status, error) {
               console.error('Upload Failed', error);
               try {
                  let responseObj = JSON.parse(xhr.responseText);
                  if (responseObj && responseObj.not_found_skus) {
                     erro.text('Some SKU codes were not found in the product list. Not found SKUs: ' + responseObj.not_found_skus.join(', '));
                  } else {
                     console.log(error);
                     erro.text(error);
                     if (errorObj && errorObj.message) {
                        erro.text(errorObj.message);
                     } else {
                        erro.text(error);
                     }
                  }
               } catch (e) {
                  console.log("catch");
                  erro.text("Error Uploading the File!");
               }
            }
         });
      }
      document.getElementById('excel_file').addEventListener('change', function () {
         let uploadButton = document.getElementById('uploadButton');
         uploadButton.disabled = this.files.length === 0;
      });
   </script>
@endsection
{{-- page scripts --}}
@section('scripts')
@endsection
