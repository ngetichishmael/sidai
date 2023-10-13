@extends('layouts.app')
{{-- page header --}}
@section('title', 'Add New Product')
@section('content')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Common Products</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/warehousing">warehouses</a></li>
                            <li class="breadcrumb-item active">Create Common Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form class="needs-validation" action="{{ route('products.StoreCommonProducts') }}" method="POST" enctype="multipart/form-data">
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
{{--                                <input type="text"  name="code" id="code" value="" />--}}
                                <input type="hidden"  name="code" id="code" value="{{$account}}" />
                            </div>
                        </div>
                    </div></div>
            </section>
            <section id="card-demo-example">
               @if($account=='nsm-admin' || $account=='rsm')
               @endif
                  <div class="row" >
                     @if($account=='nsm-admin' || $account=='rsm')
                        <div class="col-md-12 mt-2" id="diffPrice-option">
                           <input type="hidden" name="isDifferent" value="0">
                           <h5><input name="is_diffPrice" type="checkbox" id="is-diffPrice" value="1">&nbsp; This product has different price for different warehouses</h5>
                        </div>
                        <div class="col-md-8 col-lg-8" id="diffPrice-section">
                           <div class="card">
                              <div class="card-header">
                                 <h4 class="card-title">Product Different Prices</h4>
                              </div>
                           <div class="table-responsive ml-2">
                              <table id="diffPrice-table" class="table table-hover">
                                 <thead>
                                 <tr>
                                    <th width="40%">Warehouses</th>
                                    <th width="20%">Whole Sale Price</th>
                                    <th width="20%">Retail Sale Price</th>
                                    <th width="20%">Distributor Sale Price</th>
                                 </tr>
                                 @foreach($code as $warehouse)
                                    <tr>
                                       <td>
                                          <input type="hidden" name="warehouse_codes[]" value="{{$warehouse->warehouse_code}}">
                                          {{$warehouse->name}}
                                       </td>
                                       <td><input type="number" name="diff_buying_price[]" class="form-control"></td>
                                       <td><input type="number" name="diff_selling_price[]" class="form-control"></td>
                                       <td><input type="number" name="diff_distributor_price[]" class="form-control"></td>
                                    </tr>
                                 @endforeach
                                 </thead>
                                 <tbody>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        </div>
                        <div class="col-md-7 col-lg-4" id="samePrice-section">
                           <div class="card">
                              <div class="card-header">
                                 <h4 class="card-title">Product Same Prices For All Warehouses</h4>
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
                                           name="selling_price" class="form-control" placeholder="Retail Price"
                                           onchange="check()" />
                                 </div>
                                 <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Distributor Price</label>
                                    <input type="number" min="10" max="1000000" id="distributor_price"
                                           name="distributor_price" class="form-control" placeholder="Distributor Price"
                                           onchange="check()" />
                                 </div>
                                 <span style="color:#ff9398; visibility: hidden" id="msg">Notice!! Your selling price
                                    is less than buying price</span>
                              </div>
                           </div>
                        </div>
                     @endif
                     @if($account=='s-a')
                        <input type="hidden" name="warehouse_code1" value="{{$code->warehouse_code}}">
                           <div class="col-md-6 col-lg-4">
                              <div class="card">
                                 <div class="card-header">
                                    <h4 class="card-title">Product Price for {{$code->name ?? ''}}</h4>
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
                     @endif
{{--                    <div class="col-md-4 col-lg-2">--}}
{{--                    </div>--}}
                    <div class="col-md-4 col-lg-3">
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
                    <a href="{{ URL('/warehousing') }}" type="reset" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </section>
        </div>
    </form>
    <!-- Examples -->
    <!-- /Validation -->

@endsection
{{-- page scripts --}}
@section('scripts')
    <script type="text/javascript">
       $("#diffPrice-section").hide();
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

       $("input[name='is_diffPrice']").on("change", function () {
          if ($(this).is(':checked')) {
             $("#diffPrice-section").show();
             $("#samePrice-section").hide();
             $("#isDifferent").val(1);
          }
          else {
             $("#diffPrice-section").hide();
             $("#samePrice-section").show();
             $("#isDifferent").val(0);
          }
       });
    </script>
@endsection
