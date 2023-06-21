@extends('layouts.app')
{{-- page header --}}
@section('title', 'Archived Supplier List')

{{-- content section --}}
@section('content')
           <div class="content-header row">
              <div class="content-header-left col-md-12 col-12 mb-2">
                 <div class="row breadcrumbs-top">
                    <div class="col-12">
                       <h2 class="content-header-title float-start mb-0">Archived Supplier</h2>
                       <div class="breadcrumb-wrapper">
                          <ol class="breadcrumb">
                             <li class="breadcrumb-item"><a href="{{route('supplier')}}">Suppliers</a></li>
                             <li class="breadcrumb-item active">Archived</li>
                          </ol>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
    @include('partials._messages')
    @livewire('supplier.archived')
@endsection
{{-- page scripts --}}
@section('script')

@endsection
