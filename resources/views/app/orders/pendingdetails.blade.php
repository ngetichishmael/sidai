@extends('layouts.app')

@section('stylesheets')
    <style>
        .brc-default-l1 {
            border-color: #dce9f0 !important;
        }

        .ml-n1,
        .mx-n1 {
            margin-left: -.25rem !important;
        }

        .mr-n1,
        .mx-n1 {
            margin-right: -.25rem !important;
        }

        .mb-4,
        .my-4 {
            margin-bottom: 1.5rem !important;
        }

        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, .1);
        }

        .text-grey-m2 {
            color: #888a8d !important;
        }

        .text-success-m2 {
            color: #86bd68 !important;
        }

        .font-bolder,
        .text-600 {
            font-weight: 600 !important;
        }

        .text-110 {
            font-size: 110% !important;
        }

        .text-blue {
            color: #478fcc !important;
        }

        .pb-25,
        .py-25 {
            padding-bottom: .75rem !important;
        }

        .pt-25,
        .py-25 {
            padding-top: .75rem !important;
        }

        .bgc-default-tp1 {
            background-color: rgba(121, 169, 197, .92) !important;
        }

        .bgc-default-l4,
        .bgc-h-default-l4:hover {
            background-color: #f3f8fa !important;
        }

        .page-header .page-tools {
            -ms-flex-item-align: end;
            align-self: flex-end;
        }

        .btn-light {
            color: #757984;
            background-color: #f5f6f9;
            border-color: #dddfe4;
        }

        .w-2 {
            width: 1rem;
        }

        .text-120 {
            font-size: 120% !important;
        }

        .text-primary-m1 {
            color: #4087d4 !important;
        }

        .text-danger-m1 {
            color: #dd4949 !important;
        }

        .text-blue-m2 {
            color: #68a3d5 !important;
        }

        .text-150 {
            font-size: 150% !important;
        }

        .text-60 {
            font-size: 60% !important;
        }

        .text-grey-m1 {
            color: #7b7d81 !important;
        }

        .align-bottom {
            vertical-align: bottom !important;
        }
    </style>
@endsection
{{-- page header --}}
@section('title', 'Pending Delivery Details')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Pending Delivery Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/sokoflowadmin">Home</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('orders.index') !!}">Orders</a></li>
                            <li class="breadcrumb-item active">{!! $order->order_code !!}</li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials._messages')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div>
                                <span class="text-sm text-grey-m2 align-middle">To:</span>
                                <span class="text-600 text-110 text-blue align-middle">{{ $test->customer_name }}</span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1">
                                    Address, <span class="text-blue">{!! $test->address !!}</span>
                                </div>
                                <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b
                                        class="text-600">(+254){!! $test->phone_number !!}</b></div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1">Invoice </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">ID:</span> #{!! $order->id !!}</div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Issue Date:</span> {!! $order->created_at !!}</div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Status:</span> <span
                                        class="badge badge-warning badge-pill px-25 text-black-50">{!! $order->order_status !!}</span>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="">


                        <div class="table-responsive">
                            <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                                <thead>
                                    <tr class="text-black">
                                        <th class="opacity-2">#</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th width="140">Amount</th>
                                    </tr>
                                </thead>

                                <tbody class="text-95 text-secondary-d3">
                                    @foreach ($items as $count => $item)
                                        <tr>
                                            <td>{!! $count + 1 !!}</td>
                                            <td>{!! $item->product_name !!}</td>
                                            <td>{!! $item->allocated_quantity !!}</td>
                                            <td class="text-95">ksh{!! $item->selling_price !!}</td>
                                            <td class="text-secondary-d2">ksh{!! $item->selling_price * $item->allocated_quantity !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                Extra note such as company or payment information...
                            </div>

                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
{{--                                <div class="row my-2">--}}
{{--                                    <div class="col-7 text-right">--}}
{{--                                        SubTotal--}}
{{--                                    </div>--}}
{{--                                    <div class="col-5">--}}
{{--                                        <span class="text-120 text-secondary-d1">Ksh {!! $sub->sum('located_subtotal') !!}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row my-2">--}}
{{--                                    <div class="col-7 text-right">--}}
{{--                                        Tax (10%)--}}
{{--                                    </div>--}}
{{--                                    <div class="col-5">--}}
{{--                                        <span class="text-110 text-secondary-d1">{!! $item->taxrate !!}%</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                       <span>------------</span>
                                       </br>
                                        <span class="text-150 text-success-d3 opacity-2"> {!! $total->sum('allocated_totalamount') !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
{{--            <center><a href="{!! route('orders.delivery.allocation', $order->order_code) !!}" class="btn btn-block btn-warning mb-2">Allocate Delivery</a></center>--}}
            @if ($payment)
                <div class="card">
                    <div class="card-header">Order Payments</div>
                    <div class="card-body">
                        <h6>
                            <b>Amount:</b> {!! $payment->amount !!} <br>
                            <b>Payment Date:</b> {!! $payment->payment_date !!}<br>
                            <b>Payment Method:</b> {!! $payment->payment_method !!}<br>
                        </h6>
                        <hr>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">Order Payments</div>
                    <div class="card-body">
                        <h6>
                            <b>Amount:</b> N/A <br>
                            <b>Payment Date:</b> N/A <br>
                            <b>Payment Method:</b> N/A <br>
                        </h6>
                        <hr>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection
