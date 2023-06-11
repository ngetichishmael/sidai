@extends('layouts.app')
{{-- page header --}}
@section('title', 'Restock Product')


{{-- content section --}}
@section('content')
    <!-- begin page-header -->
    <h3 class="page-header"> Restock Products</h3>
    <!-- end page-header -->
    <form class="needs-validation" action="{{ route('products.updatestock', [
        'id' => $id,
    ]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="container">
            <section class="bs-validation card">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product Information</h4>
                            </div>
                            <div class="card-body">
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label class="form-label" for="basic-addon-name">Product Name</label>--}}
                                    <input type="hidden" id="basic-addon-name" class="form-control"
                                        value="{{ $product_information->product_name }}" placeholder="Enter Product Name"
                                        aria-label="Name" name="product_name" aria-describedby="basic-addon-name"
                                       />
{{--                                </div>--}}
                                <div class="mb-2 form-group">
                                    <label class="form-label" for="basic-default-email1">SKU CODE</label>
                                    <input type="text" id="basic-default-email1" class="form-control"
                                        value="{{ $product_information->sku_code }}" placeholder="SKU CODE" name="sku_code"
                                        required />
                                </div>
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Is currently Available?</label>--}}
{{--                                    <select class="form-control select" id="select-country1" name="status" required>--}}
{{--                                        <option value="Active"--}}
{{--                                            {{ $product_information->active === 'Active' ? 'selected' : '' }}>Active--}}
{{--                                        </option>--}}
{{--                                        <option value="No"--}}
{{--                                            {{ $product_information->active === 'No' ? 'selected' : '' }}>No</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Additional Information</h4>
                            </div>
                            <div class="card-body">
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Brands </label>--}}
{{--                                    <select class="select2 form-control"name="brandID" id="brandID" required>--}}
{{--                                        @foreach ($brands as $brand)--}}
{{--                                            <option value="">--Please choose the Brand--</option>--}}
{{--                                            <option value="{{ $brand }}"--}}
{{--                                                {{ $product_information->brand == $brand ? 'selected' : '' }}>--}}
{{--                                                {{ $brand }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                                <div class="mb-2 form-group">
                                    <label for="select-country1">Suppliers </label>
                                    <select name="supplierID" id="brandID" class="form-control select2" required>
                                        @foreach ($suppliers as $id => $supplier)
                                            <option value="{{ $supplier }}"
                                                {{ $product_information->supplierID == $id ? 'selected' : '' }}>
                                                {{ $supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
{{--                                <div class="mb-2 form-group">--}}
{{--                                    <label for="select-country1">Product Catergory</label>--}}
{{--                                    <select name="category" id="category" class="form-control select2">--}}
{{--                                        <option value="">--Please choose the catergory--</option>--}}
{{--                                        @foreach ($categories as $category)--}}
{{--                                            <option value="{{ $category }}"--}}
{{--                                                {{ $product_information->category == $category ? 'selected' : '' }}>--}}
{{--                                                {{ $category }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="card-demo-example">
                <div class="row">
{{--                    <div class="col-md-6 col-lg-4">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-header">--}}
{{--                                <h4 class="card-title">Product Price</h4>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="basic-default-name">Whole Sale</label>--}}
{{--                                    <input type="number" min="10" max="100000" class="form-control"--}}
{{--                                        value={{ $product_price->buying_price }} id="basic-default-name"--}}
{{--                                        name="buying_price" placeholder="Whole Sale" />--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="basic-default-email">Retail Price</label>--}}
{{--                                    <input type="number" min="10" max="100000" id="basic-default-email"--}}
{{--                                        value={{ $product_price->selling_price }} name="selling_price" class="form-control"--}}
{{--                                        placeholder="Selling Price" />--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-8 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Inventory</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Available Stock</label>
                                    <input type="number" min="10" max="100000" class="form-control"
                                        value={{ $product_inventory->current_stock }} id="basic-default-name"
                                        name="current_stock" placeholder="Available Quantity" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-email">Reorder Point</label>
                                    <input type="number" min="10" max="100000" id="basic-default-email"
                                        value={{ $product_inventory->reorder_point }} name="reorder_point"
                                        class="form-control" placeholder="Reorder Point" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-email">Reorder Quantity</label>
                                    <input type="number" min="10" max="100000" id="basic-default-email"
                                        value={{ $product_inventory->reorder_qty }} name="reorder_qty"
                                        class="form-control" placeholder="Reorder Quantity" />
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-md-6 col-lg-4">--}}
{{--                        <div class="card match-height">--}}
{{--                            <img id="output" class="card-img-top"--}}
{{--                                src="{{ asset(Storage::url($product_information->image)) }}" alt="Card image cap" />--}}
{{--                            <div class="card-body">--}}
{{--                                <h4 class="card-title">Upload Product Image</h4>--}}
{{--                                <label class="mb-0 btn btn-primary mr-75" for="change-picture">--}}
{{--                                    <span class="d-none d-sm-block">Upload</span>--}}
{{--                                    <input class="form-control" type="file" id="change-picture" name="image" hidden--}}
{{--                                        accept="image/png, image/jpeg, image/jpg" onchange="loadImage(event)" />--}}
{{--                                    <span class="d-block d-sm-none">--}}
{{--                                        <i class="mr-0" data-feather="edit"></i>--}}
{{--                                    </span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                <div class="mt-2 col-12 d-flex flex-sm-row flex-column" style="gap: 20px;">
                    <button type="submit" class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Save</button>
                    <a href="{{ URL('/products') }}" type="reset" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </section>
        </div>
    </form>
@endsection
{{-- page scripts --}}
@section('scripts')
    <script>
        var loadImage = function(event) {

            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
