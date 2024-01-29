<!DOCTYPE html>
<html>
<html>
<head>
   <title>{{$distributor ?? ''}} Order Invoice</title>
   <style>
      body {
         background-color: rgb(255, 128, 85);
         font-size: 11px; /* Set the base font size */
      }

      .padding {
         padding: 1rem !important;
      }

      .card {
         margin-bottom: 20px;
         border: none;
         box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
      }

      .card-header {
         background-color: #fff;
         border-bottom: 1px solid #e6e6f2;
      }

      h3 {
         font-size: 16px;
      }

      h5 {
         font-size: 12px;
         line-height: 18px;
         color: rgba(248, 72, 72, 0.7);
         margin: 0px 0px 10px 0px;
         font-family: 'Circular Std Medium';
      }

      .text-dark {
         color: rgba(248, 72, 72, 0.7) !important;
      }

      table {
         font-size: 10px;
      }
      .header {
         text-align: center;
      }
   </style>
   <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"/>
</head>
<body>

<div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
   <div class="card">
      <div class="card-header p-4">
         <div class="header mt-0 mb-0">
            <center>
               <div class="logo">
                  <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('app-assets/images/logo.png'))) }}" alt="Logo" width="120" height="60">
               </div>
               <b>  <p style="font-size: 14px;">Order Invoice</p> </b>
            </center>
         </div>
         <div class="row">
                  @php
                     $test = json_decode($test, true);
                  @endphp
                  <span class="text-sm text-grey-m2 align-middle"><strong>To: </strong></span>
                  <span class="text-600 text-110 text-blue align-middle">{{ $test['customer_name'] ?? '' }}</span>
               </div>
               <div class="text-grey-m2">
                  <div class="my-1">
                     <strong> Address: <span class="text-blue">{!! $test['address'] ?? '' !!}</span></strong>
                  </div>
                  <div class="my-1"> <strong>Phone: </strong> <i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b
                        class="text-600">(+254){!! $test['phone_number'] ?? '' !!}</b></div>
               </div>
               <br/>
               <div>
                  <span class="text-sm text-grey-m2 align-middle"><strong>Sales Person: </strong></span>
                  <span class="text-600 text-110 text-blue align-middle">{{$order['user']['name'] ?? ''}}</span>
               </div>
            </div>
            <div class="col-lg-4 col-sm-5 ml-auto">
               <div class="my-2"><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                     class="text-600 text-90"><strong>Issue Date:</strong></span> {{now()}}</div>
               <br/>
               <div ><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                     class="text-600 text-90"><strong>ID:</strong></span> #{!! $order['id'] ?? ''!!}</div>
               <div ><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                     class="text-600 text-90"><strong>Order Code:</strong></span> #{!! $order['order_code'] ?? ''!!}</div>
               <div ><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                     class="text-600 text-90"><strong>Order Date:</strong></span> {!! \Carbon\Carbon::parse($order['created_at'])->format('d/m/y H:i') ?? now() !!}
               </div>
               <div ><i data-feather="circle" class="text-blue-m2 text-xs mr-1"></i> <span
                     class="text-600 text-90"><strong>Status:</strong></span> <span
                     class="badge badge-warning badge-pill px-25 text-black-50">{{$order_status ?? 'Pending Delivery'}}</span>
               </div>
            </div>
         </div>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
               <thead class="text-lg">
               <tr>
                  <th class="center">#</th>
                  <th class="left">Product Name</th>
                  <th class="center">SKU CODE</th>
                  <th class="center">Qty</th>
                  <th class="center">Unit Price</th>
                  <th class="right">Total</th>
               </tr>
               </thead>
               <tbody>
               @foreach ($items as $count => $item)
                  <tr class="text-sm">
                     <td class="center">{!! $count + 1 !!}</td>
                     <td class="left">{{ $item['product_name'] }}</td>
                     <td class="center">{{ $item['product_information']['sku_code'] }}</td>
                     <td class="center">{{ $item['allocated_quantity'] ?? $item['quantity'] }}</td>
                     <td class="center">{{ $item['selling_price'] }}</td>
                     <td class="right">{{ $item['selling_price'] * $item['quantity'] }}</td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
         <div class="row">
            <div class="col-lg-4 col-sm-5">
            </div>
            <div class="col-lg-4 col-sm-5 ml-auto">
               <table class="table table-clear">
                  <tbody>
                  <tr>
                     <td class="left">
                        <strong class="text-dark">Subtotal</strong>
                     </td>
                     <td class="right">
                        <strong class="text-dark">{!! number_format(floatval(($sub)), 2) !!}</strong></td>
                  </tr>

                  <tr>
                     <td class="left">
                        <strong class="text-dark">Tax {{$item['taxrate'] ?? 0}}%</strong>
                     </td>
                     <td class="right">
                        <strong class="text-dark">{!! number_format(floatval(($item['taxrate']/100)*$total), 2) !!}</strong>
                     </td>
                  </tr>
                  <tr>
                     <td class="left">
                        <h6 class="text-dark">Total Amount</h6>
                     </td>
                     <td class="right">
                        <h6 class="text-dark">Ksh. {!! number_format(floatval(($total + $item['taxrate'])), 2)!!}</h6>
                     </td>
                  </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="card-footer bg-white">
         <p class="mb-0">Sidai Africa Ltd,Nairobi, Kenya, 27256-00100 </p>
      </div>
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" charset="utf-8"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js" charset="utf-8"></script>
</body>
</html>

