@extends('layouts.app')
{{-- page header --}}
@section('title','Sales Target')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-8">
         <h2 class="page-header"><i data-feather="list"></i> Sales | Edit </h2>
      </div>
   </div>
   <!-- end breadcrumb -->
   <!-- begin page-header -->

   <!-- end page-header -->
   @include('partials._messages')
   <div class="row">
      <div class="col-8">
          <div class="card">
              <div class="card-header">
                  <h4 class="card-title">Update Sales Targets</h4>
              </div>
              <div class="card-body">
                  <form class="form" method="POST"
                      action="{{ route('salestarget.update',$edit->user_code) }}">
                      @csrf
                      <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="target-column">Target</label>
                                <input type="number" id="target-column" class="form-control"
                                    placeholder="Target" name="target"
                                    value="{{ $edit->SalesTarget }}" />
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="first-name-column">Deadline</label>
                                <input type="date" id="first-name-column" class="form-control"
                                    placeholder="" name="deadline"
                                    value="" />
                            </div>
                        </div>
                      </div>

                      <div class="row">
                      <div class="my-1 col-sm-9 offset-sm-3">
                          <button type="submit" class="mr-1 btn" style="background-color: #B6121B;color:white">Update</button>
                          <a href="" class="btn btn-outline-secondary">Cancel</a>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
@endsection
