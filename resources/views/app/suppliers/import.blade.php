@extends('layouts.app')
{{-- page header --}}
@section('title', 'Import Warehouse')


{{-- content section --}}
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <h4>Upload Suppliers</h4>
                        <div class="col-md-4 mtop15">
                            <form action="{{ route('supplier.import') }}" id="import_form" enctype="multipart/form-data"
                                method="post" accept-charset="utf-8">
                                @csrf
                                <input type="hidden" name="clients_import" value="true">
                                <div class="mb-2 form-group form-group-default">
                                    <label for="file_csv" class="control-label text-danger">
                                        <small class="req text-danger">* </small>Choose CSV File
                                    </label>
                                    <input type="file" name="upload_import"
                                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        required />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success submit">Import</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
