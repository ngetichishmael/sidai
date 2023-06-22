@extends('layouts.app')
{{-- page header --}}
@section('title','Users Reports')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Reports</h2>
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
    <div class="col-md-6 center">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th width="1%">#</th>
                       <th width="20%">Name</th>
                       <th class="text-center" width="15.5%">Actions</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($reports as $report)
                    <tr>
                       <td>{!! $count++ !!}</td>
                       <td>{!! $report !!}</td>
                       <td>
                          <div class="d-flex" style="gap:20px">
                             @if($report == 'Payments Report')
                                <a href="{{ route('payments.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View</a>
                            @elseif($report == 'Regional Report')
                                <a href="{{ route('regional.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View</a>
                                @elseif($report == 'Distributors Report')
                                <a href="{{ route('distributor.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Suppliers Report')
                                <a href="{{ route('supplier.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View</a>
                                @elseif($report == 'Inventory Report')
                                <a href="{{ route('inventory.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Preorder Report')
                                <a href="{{ route('preorders.reports') }}" class="btn  btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Vansale Report')
                                <a href="{{ route('vansales.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Delivery Report')
                                <a href="{{ route('delivery.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Sidai Users Report')
                                <a href="{{ route('sidai.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Warehouse Report')
                                <a href="{{ route('warehouse.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Visitation Reports')
                                <a href="{{ route('visitation.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                                @elseif($report == 'Target Reports')
                                <a href="{{ route('target.reports') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">View </a>
                            @endif
                          </div>
                       </td>
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

