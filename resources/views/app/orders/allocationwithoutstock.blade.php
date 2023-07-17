@extends('layouts.app')

@section('stylesheets')

@endsection
{{-- page header --}}
@section('title', 'Order Assign')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Order Details | Assign Order</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Orders</a></li>
                            <li class="breadcrumb-item active">{!! $order->order_code !!}</li>
                            <li class="breadcrumb-item active">Assign Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials._messages')
    <form class="row" action="{!! route('order.create.allocateordersnostock') !!}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_code" value="{!! $order->order_code !!}">
        <input type="hidden" name="customer" value="{!! $order->customerID !!}">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <h4>Assign Order To User of customer <u>{{ $order->customer->customer_name }}</u>, Order Code
                        <u>{!! $order->order_code !!}</u></h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">Assign Stock To</label>
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
            </div>

            <button class="mt-1 btn btn-success" type="submit">Save and Allocate order</button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
                                $('#user').append('<option value="' + distributor.id +
                                    '">' + distributor.name + '</option>');
                            });
                        },
                        error: function($e) {
                            console.log($e);
                            console.log('Error occurred during AJAX request.');
                        }
                    });
                } else if (accountType) {
                    $.ajax({
                        url: '{{ route('get.users') }}',
                        type: 'GET',
                        data: {
                            account_type: accountType
                        },
                        success: function(data) {
                            $('#user').empty();
                            $('#user').append('<option value="">Choose a User</option>');
                            data.users.forEach(function(user) {
                                $('#user').append('<option value="' + user.user_code +
                                    '">' + user.name + '</option>');
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


@endsection
