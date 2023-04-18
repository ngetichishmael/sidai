@extends('layouts.app')
{{-- page header --}}
@section('title', 'Zones')
{{-- page styles --}}

{{-- content section --}}
@section('content')
    @include('partials._messages')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Zones</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Zones</a></li>
                            <li class="breadcrumb-item active"><a href="#">All</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h4>Upload Regions</h4>
        <div class="col-md-4 mtop15">
            <form action="{{ route('regions.update', ['region' => 1]) }}" id="import_form" enctype="multipart/form-data"
                method="post" accept-charset="utf-8">
                @csrf
                @method('PATCH')
                <input type="hidden" name="clients_import" value="true">
                <div class="mb-2 form-group form-group-default">
                    <label for="file_csv" class="control-label text-danger">
                        <small class="req text-danger">* </small>Choose CSV File
                    </label>
                    <input type="file" name="upload_import"
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success submit">Import</button>

                </div>
            </form>
        </div>
    </div>
    <!-- begin card -->
    <div class="row">
        @livewire('territory.region.dashboard')
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-body">
                    <div class="card-body">
                        <h4 class="card-title">Add Zone</h4>
                        {!! Form::open(['route' => 'regions.store']) !!}
                        @csrf
                        <div class="form-group form-group-default required">
                            {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                            {!! Form::text('name', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Zone Name',
                                'required' => '',
                            ]) !!}
                        </div>
                        <div class="mt-4 form-group">
                            <center>
                                <button type="submit" class="btn btn-success submit"><i class="fas fa-save"></i> Add
                                    Zone</button>
                            </center>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
