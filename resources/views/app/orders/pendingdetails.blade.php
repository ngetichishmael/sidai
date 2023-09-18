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
                            <li class="breadcrumb-item"><a href="{!! route('orders.pendingdeliveries') !!}">Pending Orders</a></li>
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
        <div class="col-md-10">
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
                                            <td class="text-secondary-d2">{!! $item->selling_price * $item->allocated_quantity !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
{{--                                Extra note such as company or payment information...--}}
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

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2 font-bold">
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                       <span>-----------</span>
                                       </br>
                                        <span class="text-150 text-success-d3 opacity-2 "> {!! $total->sum('allocated_totalamount') !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />
                       <br/>
                   <form class="row " action="{!! route('order.create.reallocateorders') !!}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <h2 class="content-header-title float-start mb-0">Unallocated Items</h2>
                      <input type="hidden" name="order_code" value="{!! $order->order_code !!}">
                      <input type="hidden" name="customer" value="{!! $order->customerID !!}">
                      <div class="col-md-12">

                         <div class="mt-2 card">
                            <div class="card-body">
                               <h4>Items</h4>
                               <hr>

                               @foreach ($items as $key => $item)
                                  @if ($items->isEmpty())
                                     <div class="col-md-12 ml-5">
                                        <div class="form-group">
                                           <label for="">No unallocated Items for this order...</label>
                                        </div>
                                     </div>
                                  @else
                                  @if ((int)$item->allocated_quantity < (int)$item->quantity )
                                  <input type="text" name="item_code[]" value="{!! $item->productID !!}">
                                  <div class="mb-1 row mt-2">
                                     <div class="col-md-4">
                                        <div class="form-group">
                                           <label for="">Product</label>
                                           <input type="text" name="product[]"value="{!! $item->product_name !!}"
                                                  class="form-control" readonly>
                                        </div>
                                     </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                           <label for="">Quantity</label>
                                           <input type="text" name="requested[]" value="{!! ((int)$item->quantity) - ((int)$item->allocated_quantity)!!}"
                                                  class="form-control" readonly>
                                        </div>
                                     </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                           <label for="">Total Price</label>
                                           <input type="text" name="total" value="{!! $item->selling_price * (((int)$item->quantity) - ((int)$item->allocated_quantity)) !!}"
                                                  class="form-control" style="background: rgba(255,86,86,0.7); color: rgba(0,0,0,0.82)" readonly>
                                        </div>
                                     </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                           <label for="">Allocate</label>
                                           <input type="number" name="allocate[]" class="form-control" placeholder="max {!! (((int)$item->quantity) - ((int)$item->allocated_quantity)) !!}" max="{!!(((int)$item->quantity) - ((int)$item->allocated_quantity)) !!}" required oninput="calculatePrice(this, {!! $item->selling_price !!})">
                                        </div>
                                     </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                           <label for="">Updated Price</label>
                                           <input type="number" name="price[]" class="form-control" style="background: #fa8760; color: rgba(0,0,0,0.82)" required readonly>
                                        </div>
                                     </div>
                                  </div>
                                     <script>
                                        function calculatePrice(input, sellingPrice) {
                                           const allocatedQuantity = input.value;
                                           const totalPrice = allocatedQuantity * sellingPrice;
                                           const priceInput = input.closest('.col-md-2').nextElementSibling.querySelector('input[name="price[]"]');
                                           priceInput.value = totalPrice;
                                        }
                                     </script>
                                     @endif
                                  @endif
                               @endforeach
                               @if ($items->isEmpty())
                                  <div class="col-md-12 ml-5">
                                     <div class="form-group">
                                        <label for="">No unallocated Items for this order...</label>
                                     </div>
                                  </div>
                               @endif
                            </div>
                         </div>
                      </div>
                         <hr/>
                         <div id="myDiv" style="display: none;" class="card">
                            <div class="card-body">
                               <div class="row">
                                  <div class="form-group col-md-4">
                                     <label for="">Re-assign Stock To</label>
                                     <select name="account_type" class="form-control select" id="account_type" required>
                                        <option value="">Choose User Type</option>
                                        @foreach ($account_types as $account)
                                           <option value="{!! $account->account_type !!}">{!! $account->account_type !!}</option>
                                        @endforeach
                                        <option value="distributors">Distributors</option>
                                     </select>
                                  </div>
                                  <div class="form-group col-md-4">
                                     <label for="">Choose User</label>
                                     <select name="user" class="form-control select2" id="user" required>
                                        <option value=""></option>
                                     </select>
                                  </div>
                                  <div class="form-group col-md-4 ml-3">
                                     <label for="noteText">Note</label>
                                     <textarea name="note" class="form-control" id="noteTxt" rows="3" placeholder="Provide a description"></textarea>
                                  </div>
                               </div>
                            </div>
                            <div class="mb-1 col-md-3 mr-0">
                            <button class="mt-1 btn btn-success bt-md" type="submit">Re-allocate order items</button>
                            </div>
                         </div>
                   </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script>
          document.getElementById("myDiv").style.display = "block";
          $(document).ready(function() {
             $('#account_type').on('change', function() {
                var accountType = $(this).val();
                if (accountType === 'distributors') {
                   $.ajax({
                      url: '{{ route('get.distributors') }}',
                      type: 'GET',
                      success: function(data) {
                         $('#user').empty();
                         $('#user').append('<option value="">Choose a Distributor</option>');
                         data.users.forEach(function(distributor) {
                            $('#user').append('<option value="' + distributor.id + '">' + distributor.name + '</option>');
                         });
                      },
                      error: function() {
                         console.log('Error occurred during AJAX request.');
                      }
                   });
                } else if (accountType) {
                   $.ajax({
                      url: '{{ route('get.users') }}',
                      type: 'GET',
                      data: { account_type: accountType },
                      success: function(data) {
                         $('#user').empty();
                         $('#user').append('<option value="">Choose a User</option>');
                         data.users.forEach(function(user) {
                            $('#user').append('<option value="' + user.user_code + '">' + user.name + '</option>');
                         });
                      },
                      error: function() {
                         console.log('Error occurred during AJAX request.');
                      }
                   });
                } else {
                   $('#user').empty();
                   $('#user').append('<option value="">Choose User</option>');
                }
             });
          });
       </script>
@endsection
{{-- page scripts --}}
@section('script')
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection
