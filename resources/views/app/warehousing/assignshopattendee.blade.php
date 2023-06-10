@extends('layouts.app')
{{-- page header --}}
@section('title','Assign Shop Attendee')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-8">
         <h2 class="page-header"><i data-feather="list"></i> Assign Shop Attendee </h2>
      </div>
   </div>
   <!-- end breadcrumb -->
   <!-- begin page-header -->

   <!-- end page-header -->
   @include('partials._messages')
   @livewire('warehousing.assign-shop-attendee')


{{--   <div>--}}
{{--      <div class="col-lg-12 col-12">--}}
{{--         <div class="card">--}}
{{--            <h5 class="card-header">Assign Target</h5>--}}
{{--         </div>--}}
{{--         <div class="card">--}}
{{--            <div class="card-body p-0">--}}
{{--               <div>--}}
{{--                  <table class="table">--}}
{{--                     <thead class="thead-light">--}}
{{--                     <tr>--}}
{{--                        <th>Sales Force</th>--}}
{{--                        <th>Action</th>--}}
{{--                     </tr>--}}
{{--                     </thead>--}}
{{--                     <tbody>--}}
{{--                     @foreach ($warehouse as $index => $target)--}}
{{--                        <tr class="col-8">--}}
{{--                           <td>--}}
{{--                              <label for="fp-date-time">Shop Attendee</label>--}}
{{--                              <select class="form-control--}}
{{--                                          @error('warehouse')--}}
{{--                                          border border-danger--}}
{{--                                          @enderror ">--}}
{{--                                 <option value=""> -- choose Shop Attendee -- </option>--}}
{{--                                 <option value="ALL">ALL</option>--}}
{{--                                 @foreach ($shopattendee as $user)--}}
{{--                                    <option value="{{ $user->user_code }}">--}}
{{--                                       {{ $user->name }}--}}
{{--                                    </option>--}}
{{--                                 @endforeach--}}
{{--                              </select>--}}
{{--                              @error('warehouse')--}}
{{--                              <span class="error">{{ $message }}</span>--}}
{{--                              @enderror--}}
{{--                           </td>--}}
{{--                           <td>--}}
{{--                              <a type="button" class="btn btn-outline-danger" href="#"--}}
{{--                                 wire:click="removeTargets({{ $index }})">--}}
{{--                                 <i data-feather="trash-2" class="mr-25"></i>--}}
{{--                                 <span>Delete</span>--}}
{{--                              </a>--}}
{{--                           </td>--}}
{{--                        </tr>--}}
{{--                     @endforeach--}}
{{--                     </tbody>--}}
{{--                  </table>--}}
{{--                  <div class="row">--}}
{{--                     <div class="col-md-12 m-2">--}}
{{--                        <button wire:click.prevent="addTargets" type="button" class="btn btn-outline-primary">--}}
{{--                           <i data-feather="user-plus" class="mr-25"></i>--}}
{{--                           <span>Add New Row</span>--}}
{{--                        </button>--}}
{{--                     </div>--}}
{{--                  </div>--}}
{{--               </div>--}}

{{--               @error('warehouse')--}}
{{--               <span class="error">{{ $message }}</span>--}}
{{--               @enderror--}}
{{--                  <div class="m-2">--}}
{{--                     <button wire:click.prevent="submit()" type="submit"--}}
{{--                             class="btn btn-primary mr-1 data-submit">Submit</button>--}}
{{--                  </div>--}}

{{--            </div>--}}
{{--         </div>--}}
{{--      </div>--}}
{{--   </div>--}}


@endsection
