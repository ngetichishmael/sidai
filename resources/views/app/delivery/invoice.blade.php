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
@section('title', 'Delivery Details')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Delivery Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/sokoflowadmin">Home</a></li>
                            <li class="breadcrumb-item"><a href="/delivery">Delivery</a></li>
                            <li class="breadcrumb-item active">{!! $code !!}</li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials._messages')
    <!-- Invoice -->
    <div class="row">
    <div class="col-xl-9 col-l-10 col-md-8 col-sm-12">
        <div class="card invoice-preview-card">
            <div class="pb-0 card-body invoice-padding">
                <!-- Header starts -->
                <div class="mt-0 d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                    <div>
                        <div class="logo-wrapper">
                            <img style="height:50px;" src={{ asset('app-assets/images/sidaiweblogo.png') }} alt="sidai" />
                        </div>

                          <div class="invoice-title mt-1 mb-1">
                             <strong>Customer Name : </strong>
                             <span class="invoice-name">&nbsp;{{ $deliveries->Customer->customer_name ?? '' }}</span><br/>
                             <strong>Order Code : </strong>
                             <span class="invoice-name mt-1">&nbsp;{{ $deliveries->order_code ?? '' }}</span><br/>
                             <strong>Date Processed :</strong>
                             <span class="invoice-name mt-1">&nbsp;{{ $deliveries->created_at ?? '' }}</span><br/>
                             <strong>Status :</strong>
                             <span class="invoice-name">&nbsp;{{ $deliveries->delivery_status ?? '' }}</span><br/>
                             <div class="order-2 mt-3 col-md-6 order-md-1 mt-md-0">
                                <p class="mb-0 card-text">
                                   <span class="font-weight-bold"><strong>Salesperson:</strong></span> <span class="ml-75"> {{ $deliver->User->name ?? '' }}</span>
                                </p>
                             </div>
                          </div>

                       </div>

                    <div class="mt-2 mt-md-0">

{{--                       @foreach ($deliveries as $count => $deliver)--}}
                        <h4 class="invoice-title">
                            <strong>Delivery Code: </strong>
                            <span class="invoice-number">{{ $deliveries->delivery_code }}</span>
                        </h4>
                        <div class="invoice-date-wrapper">
                            <strong>Delivery Date:</strong>
                            <span class="invoice-date">{{ $deliveries->delivered_time ?? 'Not Set' }}</span>
                        </div>
                    </div>

                </div>

                <!-- Header ends -->

            </div>

            <hr class="invoice-spacing" />

                <!-- Invoice Description starts -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="py-1">Product </th>
                                <th class="py-1"></th>
                                <th class="py-1">Quantity</th>
                                <th class="py-1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deliveries->OrderItems as $key => $value)
                                <tr>
                                    <td class="py-1">
                                        <p class="card-text font-weight-bold mb-25">{{ $value->product_name }}</p>
                                    </td>
                                    <td class="py-1">
                                        <span class="font-weight-bold"></span>
                                    </td>
                                    @php
                                       $subtotal=$subtotal+$value->sub_total;
                                    @endphp
                                    <td class="py-1">
                                        <span class="font-weight-bold">{{ $value->quantity }}</span>
                                    </td>
                                    @php
                                       $total=$total+$value->total_amount;
                                    @endphp
                                    <td class="py-1">
                                        <span class="font-weight-bold">{{ number_format($value->total_amount) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- {{ dd($total) }} --}}
                <hr class="invoice-spacing" />
                <div class="pb-0 card-body invoice-padding">
                    <div class="row invoice-sales-total-wrapper">
                        <div class="order-2 mt-3 col-md-6 order-md-1 mt-md-0">
                            <p class="mb-0 card-text">
{{--                                <span class="font-weight-bold"><strong>Salesperson:</strong></span> <span class="ml-75"> {{ $deliver->User->name ?? '' }}</span>--}}
                            </p>
                        </div>
                        <div class="order-2 col-md-6 d-flex justify-content-end col-md-2">
                            <div class="col-6">
                                <div class="invoice-total-item">
                                    <p class="invoice-total-title"><strong>Subtotal:</strong></p>
                                    <p class="invoice-total-amount">KSH {{ number_format($subtotal) }}</p>
                                </div>
                                <hr class="my-50" />
                                <div class="invoice-total-item">
                                    <p class="invoice-total-title"><strong>Total:</strong></p>
                                    <p class="invoice-total-amount">KSH: {{ number_format($total )}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--            @endforeach--}}
            <!-- Invoice Description ends -->

            <hr class="invoice-spacing" />


        </div>

    </div>

    <div class="col-xl-3 col-l-2 col-md-4 col-sm-12">
    <div class="card">
       <div class="card-header font-bolder font-medium"><h4><b class="dotted-underline">Delivery Note </b></h4></div>
       <div class="card-body">
          <h5>
             {{$deliveries->delivery_note ?? ''}}<br/>
          </h5>
          <h6>
             {{$deliveries->Note ?? ''}}<br>
          </h6>
          <hr>
       </div>
    </div>
    </div>
    </div>

    <!-- /Invoice -->
@endsection
{{-- page scripts --}}
@section('script')

@endsection
