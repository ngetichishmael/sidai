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
@section('title', 'Order Details')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Order Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/sokoflowadmin">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pendingorders">Orders</a></li>
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
                       <div class="logo-wrapper">
                          <img style="height:50px;" src={{ asset('app-assets/images/sidaiweblogo.png') }} alt="sidai" />
                       </div>
                        <div class="col-sm-6 mt-1"> <span class="text-700"><b>Customer Details</b></span>
                            <div class="mt-1">
                                <span class="text-600 text-grey-m2 align-middle"> Name:</span>
                                <span class="text-500 text-110 text-blue align-middle">{{ $test->customer_name ??'' }}</span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1 text-600">
                                    Address, <span class="text-blue">{!! $test->address ??'' !!}</span>
                                </div>
                                <div class="my-1"><i data-feather="phone" class=" fa-flip-horizontal text-secondary"></i> <b
                                        class="text-500">(+254){!! $test->phone_number ?? '' !!}</b></div>
                            </div>
                           <div class="mb-2">
                           <p class="mb-1 card-text">
                              <span class="font-weight-bold"><strong>Salesperson:</strong></span> <span class="ml-75">
                                {{ $order->User->name ?? ''}}</span>
                           </p>
                           </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1">Invoice </div>

                                <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">ID:</span> #{!! $order->id !!}</div>

                                <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Issue Date:</span> {!! $order->created_at !!}</div>

                                <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Status:</span> <span
                                        class="badge badge-warning badge-pill px-25 text-black-50">@if(strtolower($order->order_status) == "pending delivery") {{"Pending Order"}}@else {!! $order->order_status !!}@endif</span>
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
                                            <td>{!! $item->allocated_quantity?? $item->quantity !!}</td>
                                            <td class="text-95">ksh{!! $item->selling_price !!}</td>
                                            <td class="text-secondary-d2">ksh{!! $item->selling_price * $item->quantity !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                       <hr class="invoice-spacing" />
                       <div class="pb-0 card-body invoice-padding">
                          <div class="row invoice-sales-total-wrapper">
                             <div class="order-2 mt-3 col-md-6 order-md-1 mt-md-0">
                                <p class="mb-0 card-text">
{{--                                   <span class="font-weight-bold"><strong>Salesperson:</strong></span> <span class="ml-75">--}}
{{--                                {{ $order->User->name ?? ''}}</span>--}}
                                </p>
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

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2 font-bold">
                                   <div class="col-7 text-right">
                                      Total Amount
                                   </div>
                                   <div class="col-5">
                                      <hr class="my-50" />
                                      <br/>
                                      <span class="text-150 text-success-d3 opacity-2 ">KSh.  {{ number_format($total->sum('total_amount')) ?? 0}}</span>
                                   </div>
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
           <form action="{{ route('generateOrderPdf') }}" method="POST" id="pdfForm">
              @csrf
              @method('POST')
              <button type="button" class="btn btn-secondary mb-2" id="downloadBtn" onclick="generatePDF()">Download Invoice</button>
           </form>
        @if(strtolower($order->order_status) =='waiting approval')
            <form id="statusForm1" action="{!! route('orders.distributorschangeStatus', $order->order_code) !!}" method="POST">
               @csrf
               @if(Auth::user()->account_type=='Admin')
                  @if ($approval==null || ($approval->admin_id == null || $approval->admin_id == Auth::user()->id))
                  <label class="font-bold">Update Order Approval Status As an Admin</label>
                  <select id="orderStatus1" name="order_status1" class="form-control mb-2 mt-2" required>
                     <option value=""> Select </option>
                     <option value="Approved" id="approved" style="color: #51d751">Approve Order</option>
                     <option value="Disapproved" id="disapproved" style="color: orangered">Disapprove Order</option>
                  </select>
                  <input type="hidden" name="distributor" value="distributor">
                  <div id="reasonInput" style="display: none;">
                     <label for="disapprovalReason">Reason for Disapproval:</label>
                     <input type="text" id="disapprovalReason" name="disapproval_reason" class="form-control mb-2 mt-2">
                  </div>
                  <button type="submit" class="btn btn-block btn-warning">Update</button>
               @else
                  <label>Order was Approved/Disapproved by : {{$approval->admin->name ?? ''}} </label>
               @endif
               @elseif(Auth::user()->account_type=='NSM')
                  @if ($approval==null || ($approval->manager_id == null || $approval->manager_id == Auth::user()->id))
                  <label class="font-bold">Update Order Approval Status As Manager</label>
                  <select id="orderStatus1" name="order_status1" class="form-control mb-2 mt-2" required>
                     <option value=""> Select </option>
                     <option value="Approved" id="approved" style="color: #51d751">Approve Order</option>
                     <option value="Disapproved" id="disapproved" style="color: orangered">Disapprove Order</option>
                  </select>
                     <input type="hidden" name="distributor" value="distributor">
                  <div id="reasonInput" style="display: none;">
                     <label for="disapprovalReason">Reason for Disapproval:</label>
                     <input type="text" id="disapprovalReason" name="disapproval_reason" class="form-control mb-2 mt-2">
                  </div>
                  <button type="submit" class="btn btn-block btn-warning">Update</button>
                  @else
                     <label>Order was Approved/Disapproved by : {{$approval->manager->name ?? ''}} </label>
                  @endif
               @endif

            </form>
              @elseif(strtolower($order->order_status) =='partially approved')
              <label>Order was Approved/Disapproved by : {{$approval->manager->name ?? $approval->admin->name ?? ''}} </label>
              <label><h4>Order Waiting @if($approval->manager_id==null) <b>Manager Approval</b> @else <b>Administrator Approval </b>@endif </h4></label>
           @elseif($approval && (strtolower($approval->manager_status) =='disapproved' || strtolower($approval->admin_status) =='disapproved'))
              <label style="color: orangered">Order was Disapproved by : {{$approval->manager->name ?? $approval->admin->name ?? ''}} </label>
           @else
           <div>
           <form action="{{ route('generateOrderPdf') }}" method="POST" id="pdfForm">
              @csrf
              @method('POST')
              <input type="hidden" name="test" value="{{ json_encode($test) }}">
              <input type="hidden" name="order" value="{{ json_encode($order) }}">
              <input type="hidden" name="distributor" value="Sidai">
              <input type="hidden" name="items" value="{{ json_encode($items) }}">
              <input type="hidden" name="sub" value="{{ $sub->sum('sub_total') }}">
              <input type="hidden" name="total" value="{{ $total->sum('total_amount') }}">
              <input type="hidden" name="order_status" value="{{ $order->order_status }}">

{{--              <button type="button" class="btn btn-secondary mb-2" id="downloadBtn" onclick="generatePDF()">Download Invoice</button>--}}
           </form>
           <center><a href="{!! route('orders.delivery.allocation', $order->order_code) !!}" class="btn btn-block btn-warning mb-2">Allocate Order With Stock</a></center>
            <center><a href="{!! route('orders.delivery.without', $order->order_code) !!}" class="btn btn-block btn-warning mb-2">Allocate Order Without Stock</a></center>
           </div>
           @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
       function generatePDF() {
          // Disable the button to prevent multiple clicks
          $('#downloadBtn').prop('disabled', true);

          // Show a loading indicator on the button
          $('#downloadBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');

          // Submit the form asynchronously using fetch
          fetch($('#pdfForm').attr('action'), {
             method: 'POST',
             body: new FormData(document.getElementById('pdfForm')),
          })
             .then(response => response.blob())
             .then(blob => {
                // Create a Blob from the response
                const url = window.URL.createObjectURL(blob);

                // Create a temporary link to initiate the download
                const a = document.createElement('a');
                a.href = url;
                a.download = 'order.pdf';

                // Append the link to the document and trigger a click event
                document.body.appendChild(a);
                a.click();

                // Remove the temporary link and enable the button
                document.body.removeChild(a);
                $('#downloadBtn').prop('disabled', false);
                $('#downloadBtn').html('Download Invoice');
             })
             .catch(error => {
                console.error('Error during PDF generation:', error);

                // Handle errors, re-enable the button, and update the UI
                $('#downloadBtn').prop('disabled', false);
                $('#downloadBtn').html('Download Invoice');
             });
       }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
       $(document).ready(function () {
          function toggleReasonInput() {
             var selectedOption = $("#orderStatus1").val();
             if (selectedOption === "Disapproved") {
                $("#reasonInput").show();
             } else {
                $("#reasonInput").hide();
             }
          }
          toggleReasonInput();
          $("#orderStatus1").on("change", function () {
             toggleReasonInput();
          });
       });
    </script>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
