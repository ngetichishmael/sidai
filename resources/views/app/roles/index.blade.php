@extends('layouts.master')
@section('title')
    {{--    @lang('translation.UsersList')--}}
@endsection
@section('css')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Users @endslot
        @slot('title') Role @endslot
    @endcomponent
    <div class="mb-2 row">
{{--        <div class="col-md-9">--}}
{{--            <h2 class="page-header"> Role</h2>--}}
{{--        </div>--}}
        <div class="col-md-3 pe-0 mr-0">
            <center>
                <a href="{!! route('users.roles.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white"><i data-feather="user-plus"></i> Add Role</a>
            </center>
        </div>
    </div>
    @include('layouts._messages')
    @livewire('users.roles.index')
@endsection
@section('script')
@endsection
