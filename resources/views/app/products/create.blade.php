@extends('layouts.app')
{{-- page header --}}
@section('title', 'Add New Product')
@section('content')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Products</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Products</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form class="needs-validation" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <section class="bs-validation card">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Information</h4>
                            </div>
                            <div class="card-body row">
                                <div class="mb-2 form-group col-md-6">
                                    <label class="form-label" for="basic-addon-name">Product Name</label>
                                    <input type="text" id="basic-addon-name" class="form-control"
                                        placeholder="Enter Product Name" aria-label="Name" name="product_name"
                                        aria-describedby="basic-addon-name" required />
                                </div>
                                <div class="mb-2 form-group  col-md-6">
                                    <label class="form-label" for="basic-addon-name">Product SKU Code</label>
                                    <input type="text" id="basic-addon-name" class="form-control"
                                        placeholder="Enter Product SKU Code" aria-label="sku_code" name="sku_code"
                                        aria-describedby="basic-addon-name" required />
                                </div>
{{--                               <div class="mb-2 form-group">--}}
{{--                                  <label for="select-country1">Brands </label>--}}
{{--                                  <select class="select2 form-control" name="brandID" id="brandID" required>--}}
{{--                                     <option value="">-- Please choose the brand--</option>--}}
{{--                                     @foreach ($brands as $brand)--}}
{{--                                        <option value='{{ $brand }}'>{{ $brand }}</option>--}}
{{--                                     @endforeach--}}
{{--                                  </select>--}}
{{--                               </div>--}}
{{--                               <div class="mb-2 form-group">--}}
{{--                                  <label for="select-country1">Suppliers </label>--}}
{{--                                  <select name="supplierID" id="brandID" class="form-control select2" required>--}}
{{--                                     <option value="">-- Please choose the supplier--</option>--}}
{{--                                     @foreach ($suppliers as $key => $supplier)--}}
{{--                                        <option value='{{ $key + 1 }}'>{{ $supplier }}</option>--}}
{{--                                     @endforeach--}}
{{--                                  </select>--}}
{{--                               </div>--}}
                               <div class="mb-2 form-group  col-md-6">
                                  <label for="select-country1">Product Catergory</label>
                                  <select name="category" id="category" class="form-control select2">
                                     <option value="">--Please choose the catergory--</option>
                                     @foreach ($categories as $category)
                                        <option value='{{ $category }}'>{{ $category }}</option>
                                     @endforeach
                                  </select>
                               </div>

                                <input type="hidden"  name="status" value="Active"/>
                                <input type="hidden"  name="brandID" id="brandID" value="Sidai"/>
{{--                                <input type="hidden"  name="code" id="code" value="{{$code}}" />--}}
                            </div>
                        </div>

{{--                    <div class="col-md-6 col-12">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-header">--}}
{{--                                <h4 class="card-title">Additional Information</h4>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Brands </label>--}}
{{--                                    <select class="select2 form-control"name="brandID" id="brandID" required>--}}
{{--                                        <option value="">-- Please choose the brand--</option>--}}
{{--                                        @foreach ($brands as $brand)--}}
{{--                                            <option value='{{ $brand }}'>{{ $brand }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Suppliers </label>--}}
{{--                                    <select name="supplierID" id="brandID" class="form-control select2" required>--}}
{{--                                        <option value="">-- Please choose the supplier--</option>--}}
{{--                                        @foreach ($suppliers as $key => $supplier)--}}
{{--                                            <option value='{{ $key + 1 }}'>{{ $supplier }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Product Catergory</label>--}}
{{--                                    <select name="category" id="category" class="form-control select2">--}}
{{--                                        <option value="">--Please choose the catergory--</option>--}}
{{--                                        @foreach ($categories as $category)--}}
{{--                                            <option value='{{ $category }}'>{{ $category }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    </div></div>
            </section>
            <section id="card-demo-example">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Price</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Whole Sale</label>
                                    <input type="number" min="10" max="1000000" class="form-control"
                                        id="buying_price" name="buying_price" placeholder="Whole Sale" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Retail Price</label>
                                    <input type="number" min="10" max="1000000" id="selling_price"
                                        name="selling_price" class="form-control" placeholder="Retail Price" required
                                        onchange="check()" />
                                </div>
                               <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Distributor Price</label>
                                    <input type="number" min="10" max="1000000" id="distributor_price"
                                        name="distributor_price" class="form-control" placeholder="Distributor Price" required
                                        onchange="check()" />
                                </div>
                                <span style="color:#ff9398; visibility: hidden" id="msg">Notice!! Your selling price
                                    is less than buying price</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-2">
{{--                        <div class="card">--}}
{{--                            <div class="card-header">--}}
{{--                                <h4 class="card-title">Inventory</h4>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="basic-default-name">Available Stock</label>--}}
{{--                                    <input type="number" min="10" max="100000" class="form-control"--}}
{{--                                        id="basic-default-name" name="current_stock" placeholder="Available Quantity" />--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="basic-default-email">Reorder Point</label>--}}
{{--                                    <input type="number" min="10" max="100000" id="basic-default-email"--}}
{{--                                        name="reorder_point" class="form-control" placeholder="Reorder Point" />--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="basic-default-email">Reorder Quantity</label>--}}
{{--                                    <input type="number" min="10" max="100000" id="basic-default-email"--}}
{{--                                        name="reorder_qty" class="form-control" placeholder="Reorder Quantity" />--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card match-height">
                            <img id="output" class="card-img-top"
                                src="{{ asset('/app-assets/images/slider/04.jpg') }}" alt="Card image cap" />
                            <div class="card-body">
                                <h4 class="card-title">Upload Product Image</h4>
                                <label class="mb-0 btn btn-primary mr-75" for="change-picture">
                                    <span class="d-none d-sm-block">Upload</span>
                                    <input class="form-control" type="file" id="change-picture" name="image" hidden
                                        accept="image/png, image/jpeg, image/jpg" onchange="loadImage(event)" />
                                    <span class="d-block d-sm-none">
                                        <i class="mr-0" data-feather="edit"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 col-12 d-flex flex-sm-row flex-column" style="gap: 20px;">
                    <button type="submit" class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Save</button>
                    <a href="{{ URL('/warehousing/'.$code.'/products') }}" type="reset" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </section>
        </div>
    </form>
    <!-- Examples -->
    <!-- /Validation -->
@endsection
{{-- page scripts --}}
@section('scripts')
    <script>
        var loadImage = function(event) {

            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
        }

        function check() {
            var sp = document.getElementById("selling_price").value;
            var bp = document.getElementById("buying_price").value;
            document.getElementById("msg").style.visibility = "hidden";
            if (bp >= sp) {
                document.getElementById("msg").style.visibility = "visible";
            }
        }
    </script>
@endsection
