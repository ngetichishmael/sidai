@extends('layouts.app')
{{-- page header --}}
@section('title', 'Items')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Stock Lifts | items</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials._messages')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-inverse">
                <div class="card-body">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Allocated Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->productInformation->product_name }}</td>
                                    <td>{{ $item->allocated_qty }}</td>
{{--                                    <td>{{ $item->returned_quantity }}</td>--}}
{{--                                    <td>--}}
{{--                                        @if ($item->productInformation->image)--}}
{{--                                            <img src="{{ $item->productInformation->image }}"--}}
{{--                                                alt="{{ $item->productInformation->product_name }}"--}}
{{--                                                style="max-width: 100px; max-height: 100px;">--}}
{{--                                        @else--}}
{{--                                            No Image Available--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       <div class="col-md-3 mt-0">
          <div class="card">
          @if ($item->productInformation->image)
{{--            <img src="{{ '/storage/'.$item->image ?? $item->productInformation->image}}"--}}
{{--             alt="{{ $item->image}}" style="max-width: 150px; max-height: 120px;">--}}
                <div class="card">
                   <div class="card-body text-center">
                      <img
                         src="{{ '/storage/'.$item->image ?? $item->productInformation->image }}"
                         alt="{{ $item->image }}"
                         style="max-width: 150px; max-height: 120px; cursor: pointer;"
                         onclick="showFullImage('{{ '/storage/'.$item->image ?? $item->productInformation->image }}')"
                      >
                   </div>
                </div>

                <!-- Modal for displaying the full image -->
                <div id="imageModal" class="modal">
                   <span class="close" onclick="closeModal()">&times;</span>
                   <img id="fullImage" class="modal-content">
                </div>

                <style>
                   .card {
                      display: flex;
                      justify-content: center;
                      align-items: center;
                      height: 200px; /* Adjust the height as needed */
                   }

                   /* Styles for the modal */
                   .modal {
                      display: none;
                      position: fixed;
                      z-index: 1;
                      padding: 200px 50px 10px;
                      /*left: 0px;*/
                      top: 80px;
                      width: 100%;
                      height: 100%;
                      background-color: rgba(0, 0, 0, 0.7);
                   }

                   .modal-content {
                      display: block;
                      margin: 0 auto;
                      max-width: 80%;
                      max-height: 80%;
                   }

                   .close {
                      position: absolute;
                      top: 20px;
                      right: 20px;
                      font-size: 30px;
                      cursor: pointer;
                      color: #fff;
                   }
                </style>

                <script>
                   function showFullImage(imageUrl) {
                      var modal = document.getElementById('imageModal');
                      var fullImage = document.getElementById('fullImage');
                      fullImage.src = imageUrl;
                      fullImage.style.width = 'auto';
                      fullImage.style.height = 'auto';
                      modal.style.display = 'block';
                   }

                   function closeModal() {
                      var modal = document.getElementById('imageModal');
                      modal.style.display = 'none';
                   }
                </script>
             @else
               No Image Available
             @endif
       </div>
       </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
