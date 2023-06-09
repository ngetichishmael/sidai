@extends('layouts.app')
{{-- page header --}}
@section('title', 'Product reports')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">All Products</h2>
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
        <div class="col-md-12">
            <div class="card card-inverse">
                <div class="card-body">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Wholesale Price</th>
                                <th>Retail Price</th>
                                <th>Distributor Price</th>
                                <th>Quantity</th>
                                <th>Sku Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <td>{!! $key + 1 !!}</td>
                                    <td>{!! $product->product_name !!}</td>
                                    @if ($product->ProductPrice->buying_price == 0 || 00)
                                        <td>{{ 'Price Not set' }}</td>
                                    @else
                                        <td>{{ number_format((float) $product->ProductPrice->buying_price) }}</td>
                                    @endif
                                    <td>
                                        {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
                                    </td>
                                    <td>
                                        {{ number_format((float) $product->ProductPrice()->pluck('distributor_price')->implode('')) }}
                                    </td>
                                    <td>{{ $product->Inventory->current_stock ?? '0' }}</td>
                                    <td>{{ $product->sku_code }}</td>
                                    @if ($product->Inventory()->pluck('current_stock')->implode('') > 1)
                                        <td><button class="btn btn-success btn-sm">{{ 'Available' }}</button></td>
                                    @elseif ($product->Inventory()->pluck('current_stock')->implode('') < 1)
                                        <td><button class="btn btn-danger btn-sm">{{ 'SOLD OUT' }}</button></td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
