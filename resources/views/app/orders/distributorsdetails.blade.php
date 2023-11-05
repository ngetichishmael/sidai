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
@section('title', 'Distributor Order Details')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Distributor Order Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/sokoflowadmin">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Distributor Orders</a></li>
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
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                   <div class="row">
                      <div class="col-sm-6">
                         <div class="logo-wrapper">
                            <img style="height:50px;" src={{ asset('app-assets/images/sidaiweblogo.png') }} alt="sidai" />
                         </div>
                         <div>
                            <span class="text-sm text-grey-m2 align-middle">Customer Name:</span>
                            <span class="text-600 text-110 text-blue align-middle">{{ $test->customer_name ??''}}</span>
                         </div>
                         <div class="text-grey-m2">
                            <div class="my-1">
                               Address, <span class="text-blue">{!! $test->address ??'' !!}</span>
                            </div>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b
                                  class="text-600">  (+254){!! $test->phone_number??'' !!}</b></div>
                         </div>
                         <div class="mt-1 mb-3">
                            <span class="text-sm text-grey-m2 align-middle">Order Assigned To: </span>
                            <span class="text-600 text-110 text-blue align-middle">  {{ $order->user->name ?? "N/A"}}</span>
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
                                            <td class="text-95">{!! $item->selling_price !!}</td>
                                            <td class="text-secondary-d2">{!! $item->selling_price * $item->quantity !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">

                            </div>

                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        SubTotal
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1">{!! number_format(floatval( $sub->sum('sub_total')),2) !!}</span>
                                    </div>
                                </div>

                                <div class="row my-2 mb-1">
                                    <div class="col-7 text-right">
                                        Tax {{$item->taxrate ?? 0}}%
                                    </div>
                                    <div class="col-5">
                                        <span class="text-110 text-secondary-d1 d-flex">&nbsp; &nbsp; &nbsp; {!!number_format(floatval(($item->taxrate/100)*$total->sum('total_amount')), 2) !!}</span>
                                    </div>
                                </div>

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2 mt-1">
                                   <hr class="my-50" />
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                        <span class="text-120 text-success-d3 opacity-2">Ksh. {!!  number_format(floatval($total->sum('total_amount') + $item->taxrate), 2) !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />
                    </div>
                </div>
            </div>
        </div>
       <div class="col-md-3 mt-2">
     <a href="{{ route('generatePdf', [
    'test' => $test, 'order' => json_encode($order),'distributor'=>$distributor, 'items' => json_encode($items), 'sub' => $sub->sum('sub_total'), 'total' => $total->sum('total_amount'),'order_status'=>$order->order_status]) }}" class="btn btn-secondary mb-2">Download Invoice</a>
          <h6 class="mt-3 mb-3">Tracking Distributor Order Status</h6>
          <span class="mt-1 mb-1">Current Status: <span id="currentStatus" style="color: orangered">@if(strtolower($order->order_status) == "pending delivery") {{"Pending Order"}} @elseif(strtolower($order->order_status) == "complete delivery" || strtolower($order->order_status) == "delivered") {{"Order Derivered"}}@else {!! $order->order_status !!}@endif</span></span>
          <center>
             <form id="statusForm" action="{!! route('orders.distributorschangeStatus', $order->order_code) !!}" method="POST">
                @csrf
                <select id="orderStatus" name="order_status" class="form-control mb-2 mt-2" required>
                   <option value={{$order->order_status}}>@if(strtolower($order->order_status) == "pending delivery") {{"Pending Order"}}@else {!! $order->order_status !!}@endif</option>
                   @if(strtolower($order->order_status) == "pending delivery")
                      <option value="Complete Delivery" id="cd">Complete Delivery</option>
                      <option value="Partially Delivered" id="pd">Partially Delivered</option>
                      <option value="Not Delivered" id="nd">Not Delivered</option>
                   @elseif(strtolower($order->order_status) == "complete delivery" || strtolower($order->order_status) == "delivered")
                      <option value="Complete Delivery" id="cd">Order Delivered</option>
                      <option value="Partially Delivered" id="pd">Partially Delivered</option>
                   @elseif(strtolower($order->order_status) == "partially delivered")
                      <option value="Complete Delivery" id="cd">Complete Delivery</option>
                      <option value="Partially Delivered" id="pd">Partially Delivered</option>
                   @elseif(strtolower($order->order_status) == "not delivered")
                      <option value="Complete Delivery" id="cd">Complete Delivery</option>
                      <option value="Not Delivered" id="nd">Not Delivered</option>
                   @endif
                </select>
                <button type="submit" class="btn btn-block btn-warning">Change Order Status</button>
             </form>
          </center>
       </div>

       <div class="row">
          <div class="col-md-5 ml-5">
             <a href="{{ url()->previous() }}" class="btn btn-info mb-2" style="align-content: center" >&nbsp; Back &nbsp; </a>
       </div>
    </div>

@endsection
{{-- page scripts --}}
@section('script')

@endsection
