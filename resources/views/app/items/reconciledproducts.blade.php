@extends('layouts.app')
{{-- page header --}}
@section('title','Items')

{{-- content section --}}
@section('content')
   <div class="content-header row" xmlns="http://www.w3.org/1999/html">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Reconciled | items</h2>
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
      <span class="pl-5 ml-5 mb-1"><strong> Requested On: </strong> {{$amounts->created_at}}</span>
    <div class="col-md-8">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Product Name</th>
                       <th>Quantity</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($reconciled as $key=>$reconcile)
                  <tr>
                     <td>{{ $key+1 }}</td>
{{--                     <td>{{ $reconcile->user }}</td>--}}
                     <td>{{ $reconcile->name }}</td>
                     <td>{{ $reconcile->amount }}</td>
{{--                     <td>{{ $reconcile->date }}</td>--}}
                 </tr>
                  @endforeach


                 </tbody>
              </table>
           </div>
        </div>
     </div>
      <div class="col-md-4 align-content-center">
         <div class="card">
            <div class="card-header">
               <h4>Expected Payment Amounts</h4>
            </div>
            <div class="card-body">
               <label><strong>Cash: </strong></label>
               <span>{{ $amounts->cash ?? 0.00  }}</span>
               <br/>
               <label><strong>Mpesa: </strong></label>
               <span>{{ $amounts->mpesa ?? 0.00  }}</span>
               <br/>
               <label><strong>Bank: </strong></label>
               <span>{{ $amounts->bank ?? 0.00  }}</span>
               <br/>
               <label><strong>Cheque: </strong></label>
               <span>{{ $amounts->cheque ?? 0.00  }}</span>
               <br/>
               <hr/>
               <label><strong>Total Amount: </strong></label>
               <span>{{ $amounts->total ?? 0.00  }}</span>
            </div>
            @if($amounts || $amounts->note!=null)
               <div class="card-footer">
                  <p>
                     <label>Reason </label>
                     <span>{{$amounts->note ?? ''}}</span>
                  </p>
               </div>
            @endif
         </div>
      </div>

      @if($amounts!=null && $amounts->status !='approved')
         <form method="POST" action="{{ route('reconciliations.handleApprovals', $amounts->reconciliation_code) }}">
         @csrf
         @method('POST')
            <div class="row">
               <div class="input-group col-md-4">
                  <span class="input-group-text">Specify a Reason</span>
                  <textarea class="form-control" name="note" aria-label="With textarea"></textarea>
               </div>
               <div class=" col-md-2">
               </div>
               <div class="col-md-4">
            <button type="submit" class="mt-1 pl-3 btn btn-primary" name="action" value="approved">Approve </button>
            <button type="submit" class="mt-1 pl-3 btn btn-danger" name="action" value="rejected">Reject </button>
            </div>
            </div>
         </form>
      @endif
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

