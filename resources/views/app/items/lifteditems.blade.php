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
       <div class="col-md-4 mt-5">
          <div class="card">
          @if ($item->productInformation->image)
                                                      <img src="{{ '/storage/image/'.$item->productInformation->image ?? $item->productInformation->image}}"
                                                          alt="{{ $item->productInformation->image}}"
                                                          style="max-width: 100px; max-height: 100px;">
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
