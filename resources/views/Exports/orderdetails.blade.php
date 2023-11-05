<!DOCTYPE html>
<html>
<head>
    <title>{{$distributor ?? ''}} Order Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        td {
        font-size: 10px; /* Adjust the font size as needed */
        }

        /* Add custom styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            font-size: 11px; /* Adjust the font size as needed */
            background-color: #f2f2f2;
        }

        /* Add custom styling for the header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Add any other custom CSS styles here */

    </style>
</head>
<body>
    <div class="header">
        <center>
        <div class="logo">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('app-assets/images/logo.png'))) }}" alt="Logo" width="150" height="80">
                </div>
          <b>  <p>Order Invoice</p> </b>
        </center>
    </div>
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
                      <span class="text-sm text-grey-m2 align-middle">To:</span>
                      <span class="text-600 text-110 text-blue align-middle">{{ $test['customer_name'] ?? ''}}</span>
                   </div>
                   <div>
                      <span class="text-sm text-grey-m2 align-middle">Sales Person:</span>
                      <span class="text-600 text-110 text-blue align-middle">{{ $order->User->name ?? ''}}</span>
                   </div>
                   <div class="text-grey-m2">
                      <div class="my-1">
                         Address, <span class="text-blue">{!! $test['address'] ?? '' !!}</span>
                      </div>
                      <div class="my-1"><i data-feather="phone" class=" fa-flip-horizontal text-secondary"></i> <b
                            class="text-600">(+254){!! $test['phone_number'] ?? '' !!}</b></div>
                   </div>
                </div>
                <!-- /.col -->

                <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                   <hr class="d-sm-none" />
                   <div class="text-grey-m2">
                      <div class="mt-1">Invoice </div>
                      <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                            class="text-600 text-90">Issue Date:</span> {{now()}}</div>
                      <br/>
                      <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                            class="text-600 text-90">ID:</span> #{!! $order['id'] ?? ''!!}</div>
                      <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                            class="text-600 text-90">Order Code:</span> #{!! $order['order_code'] ?? ''!!}</div>
                     <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                            class="text-600 text-90">Order Date:</span> {!! $order['created_at']  ?? now()!!}</div>

                      <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                            class="text-600 text-90">Status:</span> <span
                            class="badge badge-warning badge-pill px-25 text-black-50">{{$order_status}}</span>
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
                            <td>{{ $item['product_name'] }}</td>
                            <td>{{ $item['allocated_quantity'] }}</td>
                            <td class="text-95">{{ $item['selling_price'] }}</td>
                            <td class="text-secondary-d2">{{ $item['selling_price'] * $item['quantity'] }}</td>
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
                            <span class="text-120 text-secondary-d1"> {!! number_format(floatval(($sub)), 2) !!}</span>
                         </div>
                      </div>
                      <div class="row my-2 mb-1">
                         <div class="col-7 text-right">
                            Tax {{$item['taxrate'] ?? 0}}%
                         </div>
                         <div class="col-5">
                            <span class="text-110 text-secondary-d1 d-flex">&nbsp; &nbsp; &nbsp; {!! number_format(floatval(($item['taxrate']/100)*$total), 2) !!}</span>
                         </div>
                      </div>
                      <hr class="my-50"/>
                      <div class="row my-2 align-items-center bgc-primary-l3 p-2 mt-1">
                         <div class="col-7 text-right">
                            Total Amount
                         </div>
                         <div class="col-5">
                            <span class="text-120 text-success-d3 opacity-2">Ksh. {!! number_format(floatval(($total + $item['taxrate'])), 2)!!}</span>
                         </div>
                      </div>
                   </div>
                </div>

                <hr />
             </div>
          </div>
       </div>
    </div>
    </div>
</body>
</html>
