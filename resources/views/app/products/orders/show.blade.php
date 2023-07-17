@extends('layouts.app')
{{-- page header --}}
@section('title', 'View Order')
{{-- page styles --}}

@section('stylesheet')
    <style>
        .mw-ui-box-content {
            padding: 12px;
            position: relative;
        }

        .order-status-selector ul {
            list-style-position: inside;
        }

        .mw-ui-inline-list li {
            list-style: none;
        }

        .mw-ui-inline-list>* {
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }

        .mw-ui-inline-list li * {
            vertical-align: middle;
        }

        .mw-ui-check {
            cursor: pointer;
            vertical-align: middle;
            display: inline-block;
        }

        .mw-ui-check input {
            position: absolute;
            opacity: 0;
        }

        .mw-ui-check input[type=radio]+span:first-of-type {
            border-radius: 30px;
        }

        .mw-ui-check input:checked+span {
            border-color: #009cff;
        }

        .mw-ui-check input:checked+span {
            box-shadow: 0 1px 3px #ccebff;
        }

        .mw-ui-check input+span {
            display: inline-block;
            position: relative;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            cursor: pointer;
            line-height: normal;
            text-align: center;
            vertical-align: middle;
            margin-right: 8px;
            font-weight: 400;
            width: 17px;
            height: 17px;
            transition: all .3s;
            border: 1px solid #cfcfcf;
            background-color: #fff;
        }

        .mw-ui-check input:checked+span:after {
            visibility: visible;
            opacity: 1;
            transform: scale(1);
        }

        .mw-ui-check input+span:after {
            position: absolute;
            transition: all .3s;
            visibility: hidden;
            opacity: 0;
            transform: scale(.3);
            content: '';
        }

        .mw-ui-check,
        .mw-ui-check>*,
        .mw-ui-check>:before {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            vertical-align: middle;
        }

        #mw_order_status {
            margin-top: 15px;
        }

        .order-table-info-list li {
            list-style: none;
            margin-bottom: 10px;
        }

        .order-table-info-list li {
            list-style: none;
            margin-bottom: 10px;
        }

        select.mw-ui-field {
            cursor: default;
        }

        input.mw-ui-field-medium,
        select.mw-ui-field-medium {
            height: 2.308rem;
        }

        input.mw-ui-field,
        select.mw-ui-field {
            height: 2.923rem;
        }

        select.mw-ui-field {
            -webkit-appearance: menulist;
        }

        .mw-ui-field-medium {
            padding: 6px;
        }

        .mw-ui-field {
            border: 1px solid #cfcfcf;
            padding: 8px;
            max-width: 100%;
            border-radius: 2px;
            background-color: #fff;
            outline: 0;
            cursor: text;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            box-shadow: inset 0 0 3px rgba(0, 0, 0, .2);
            transition: border-color .3s, box-shadow, .3s;
        }
    </style>
@endsection
{{-- dashboad menu --}}
@section('sidebar')
    @include('app.finance.partials._menu')
@endsection

{{-- content section --}}
@section('content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{!! route('finance.index') !!}">Finance</a></li>
            <li class="breadcrumb-item"><a href="{!! route('finance.product.index') !!}">Items</a></li>
            <li class="breadcrumb-item"><a href="{!! route('finance.product.index') !!}">e-Commerce</a></li>
            <li class="breadcrumb-item"><a href="{!! route('finance.ecommerce.orders') !!}">Orders</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">View Order</h1>
        <!-- end page-header -->
        @include('partials._messages')
        @foreach ($orders as $order)
            <!-- begin panel -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Order Information</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="10%">image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th class="center">QTY</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->cart->items as $item)
                                            <tr>
                                                <td>
                                                    <img src="{!! asset('businesses/' . $order->businessID . '/finance/products/' . $item['item']['image']) !!}" alt="" width="100%">
                                                </td>
                                                <td>
                                                    <a href="#"><span>{!! $item['item']['title'] !!}</span></a>
                                                </td>
                                                <td>{!! $order->code !!} {!! $item['price'] / $item['qty'] !!}</td>
                                                <td>{!! $item['qty'] !!}</td>
                                                <td>{!! $order->code !!} {!! $item['price'] !!}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="4">TOTAL AMOUNT</td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2">Subtotal</td>
                                            <td class="" colspan="2">{!! $order->code !!}
                                                {!! number_format($order->cart->totalPrice) !!}</td>
                                        </tr>

                                        {{-- <tr class="mw-ui-table-footer">
                                 <td colspan="2">&nbsp;</td>
                                 <td colspan="2">Shipping price</td>
                                 <td class="" colspan="2">$ 0.00</td>
                              </tr>              --}}
                                        <tr class="mw-ui-table-footer last">
                                            <td colspan="2">&nbsp;</td>
                                            <td colspan="2" class=""><strong>Total</strong></td>
                                            <td class="" colspan="2"><strong>{!! $order->code !!}
                                                    {!! number_format($order->cart->totalPrice) !!}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Client Information</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <colgroup>
                                        <col width="50%">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <td>Customer Name</td>
                                            <td>{!! $order->customer_name !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><a href="{!! $order->email !!}">{!! $order->email !!}</a></td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td><b>{!! $order->primary_phone_number !!}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td><b>{!! $order->gender !!}</b></td>
                                        </tr>
                                        <tr>
                                            <td>User IP</td>
                                            <td>{!! $order->ip !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Payment Information</div>
                        <div class="panel-body">
                            <div class="mw-ui-box-content">
                                <ul class="order-table-info-list">
                                    <li>
                                        Payment Method : <strong>paypal</strong>
                                    </li>
                                    <li>
                                        Is Paid:
                                        <select name="is_paid"
                                            class="mw-ui-field mw-ui-field-medium mw-order-is-paid-change">
                                            <option value="1">Yes </option>
                                            <option value="0" selected="selected">No </option>
                                        </select>
                                    </li>
                                    <li>
                                        Payment Amount: 39
                                        <span class="mw-icon-help-outline mwahi tip"
                                            data-tip="Amount paid by the user"></span>
                                    </li>
                                    <li>Payment currency: USD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Shipping Address </div>
                        <div class="panel-body">
                            <ul class="order-table-info-list">
                                <li><strong>Country:</strong> Bulgaria</li>
                                <li><strong>City:</strong> Sofia</li>
                                <li><strong>State:</strong> Sofia</li>
                                <li><strong>ZIP:</strong>1000 </li>
                                <li><strong>Address:</strong><br>NDK</li>
                                <li><strong>Phone::</strong>00123456789 </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Order Status</div>
                        <div class="panel-body">
                            <div class="mw-ui-box-content">
                                <div class="order-status-selector">
                                    <ul class="mw-ui-inline-list">
                                        <li><span>What is the status of this order?</span></li>
                                        <li>
                                            <label class="mw-ui-check">
                                                <input checked="checked" type="radio" name="order_status" value="pending">
                                                <span></span>
                                                <span>Pending</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="mw-ui-check">
                                                <input type="radio" name="order_status" value="completed">
                                                <span></span><span>Completed Order</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div id="mw_order_status">
                                    <a href="#" class="btn btn-lg btn-warning btn-block">Pending </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
