@extends('layouts.app')
{{-- page header --}}
@section('title','Stock Recon sales person')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Stock Reconciliations</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item "><a href="/stock-Reconciliations">Reconcilition</a></li>
                        <li class="breadcrumb-item active"><a href="#">Sales Person</a></li>
                     </ol>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <style>
      .nav-tabs .nav-link.active-tab {
         border-bottom: 2px solid orange;
         color: rgba(35, 34, 34, 0.7);
      }
   </style>
   <div class="row">
      <div class="col-md-10">
         <div class="card card-inverse">
            <div class="card-body">
{{--               <div class="tabs">--}}
{{--                  <button onclick="filterTable('all')">All</button>--}}
{{--                  <button onclick="filterTable('waiting_approval')">Waiting Approval</button>--}}
{{--                  <button onclick="filterTable('approved')">Approved</button>--}}
{{--                  <button onclick="filterTable('rejected')">Rejected</button>--}}
{{--               </div>--}}
               <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link {{ $status == 'waiting_approval' ? 'active-tab' : '' }}" onclick="filterTable('waiting_approval', this)" data-status="waiting_approval">Waiting Approval</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link {{ $status == 'approved' ? 'active-tab' : '' }}" onclick="filterTable('approved', this)" data-status="approved">Approved</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link {{ $status == 'rejected' ? 'active-tab' : '' }}" onclick="filterTable('rejected', this)" data-status="rejected">Rejected</a>
                  </li>
               </ul>

               <div class="tab-content mt-3">
               <table id="data-table-default" class="table table-striped table-bordered">
                  <thead>
                  <tr>
                     <th>#</th>
                     <th>Sales Person</th>
                     <th>Total Amount</th>
                     <th>Reconciliation Code</th>
                     @if($type !=='shop-attendee')
                        <th>Warehouse</th>
                     @endif
                     <th>Date</th>
                     <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($sales as $key=>$sale)
                     <tr data-status="{{ $sale->status }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ $sale->salesPerson->name ?? ''}}</td>
                        <td>{{ $sale->total ?? 0.00 }}</td>
                        <td>{{ $sale->reconciliation_code}}</td>
                        @if($type !=='shop-attendee')<td>{{ $warehouse_name }}</td> @endif
                        <td>{{ $sale->created_at }}</td>
                        <td><a href="{{ URL('products/reconciled/' . $sale->reconciliation_code) }}" class="btn btn-sm" style="color: white;background-color:rgb(194, 51, 51)">View Products</a></td>
                     </tr>
                  @endforeach
                  </tbody>
               </table>
            </div>
            </div>
         </div>
      </div>
   </div>
   <script>
      // Initial load to set the correct data
      document.addEventListener("DOMContentLoaded", function() {
         var initialStatus = 'waiting_approval'; // Set the default status here
         var initialTab = document.querySelector(".nav-tabs a[data-status='" + initialStatus + "']");
         filterTable(initialStatus, initialTab);
      });

      function filterTable(status, clickedTab) {
         var tabs = document.querySelectorAll(".nav-tabs .nav-link");
         tabs.forEach(function(tab) {
            tab.classList.remove("active-tab");
         });

         var rows = document.querySelectorAll("#data-table-default tbody tr");
         rows.forEach(function(row) {
            if (row.getAttribute("data-status") === status || status === "all") {
               row.style.display = "";
            } else {
               row.style.display = "none";
            }
         });

         clickedTab.classList.add("active-tab");
      }
   </script>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

