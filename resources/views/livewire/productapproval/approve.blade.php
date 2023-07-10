<div>

    <div class="row">
        <div class="col-md-12">
            <div class="pt-0 card-datatable table-responsive">
                <div class="card">
                    <div class="card-header"> Stock Requisition Items</div>
                    <div class="card-body">
                       <form method="POST" action="{{ route('inventory.handleApproval') }}">
                          @csrf
{{--                          <div class="form-group mb-1 mt-2 ml-0 pe-0 col-md-5">--}}
{{--                             <label for="warehouse" style="color: #d5512d">Warehouse:--}}
{{--                                <input class="focus:bg-gray-400" readonly value="{{}}">--}}
{{--                             <select id="warehouse" class="form-control select2" name="warehouse_code" required>--}}
{{--                                <option value="" class="focus:bg-gray-400">Select a Warehouse</option>--}}
{{--                                @foreach($warehouses as $warehouse)--}}
{{--                                   <option value="{{ $warehouse->warehouse_code }}">{{ $warehouse->name }}</option>--}}
{{--                                   <option value="{{ $warehouse->warehouse_code }}">{{ $warehouse->name }}</option>--}}
{{--                                @endforeach--}}
{{--                             </select>--}}
{{--                          </div>--}}
                          <table class="table table-bordered table-striped">
                            <thead>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>SKU Code</th>
                                <th>Warehouse</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            @foreach ($products as $count => $product)
                               <tr>
                                  <td>{!! $count + 1 !!}</td>
                                  <td>{!! $product->ProductInformation->product_name??'' !!}</td>
                                  <td>{!! $product->quantity??'' !!}</td>
                                  <td>{!! $product->ProductInformation->sku_code??'' !!}</td>
                                  <td>{!! $product->ProductInformation->warehouse->name??'' !!}</td>
                                  @if ($product->approval === 1)
                                  <td style="color: green">Approved</td>
                                  @else
                                  <td>
                                    @if ($product === 1)
                                       <input type="checkbox" name="selected_products[]" value="{{ $product->id }}" checked>
                                    @else
                                       <input type="checkbox" name="selected_products[]" value="{{ $product->id }}">
                                    @endif
                                 </td>
                                  @endif
                                  
                               </tr>
                            @endforeach
                            </tbody>
                          </table>
                          <button type="submit" class=" mt-1 pl-3 btn btn-primary" name="approve">Approve and Continue </button>
                          <button type="submit" class=" mt-1 pr-3 btn btn-danger" name="disapprove">Disapprove  and Continue</button>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





{{--</div>--}}{{--<div>--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="pt-0 card-datatable table-responsive">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header"> Stock Requisition Items</div>--}}
{{--                    <div class="card-body">--}}
{{--                        <table class="table table-bordered table-striped">--}}
{{--                            <thead>--}}
{{--                                <th>#</th>--}}
{{--                                <th>Product Name</th>--}}
{{--                                <th>Quantity</th>--}}
{{--                                <th>SKU Code</th>--}}
{{--                                <th>Action</th>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                @foreach ($products as $count => $product)--}}
{{--                                    <tr>--}}
{{--                                        <td>{!! $count + 1 !!}</td>--}}
{{--                                        <td>{!! $product->ProductInformation->product_name !!}</td>--}}
{{--                                        <td>{!! $product->quantity !!}</td>--}}
{{--                                        <td>{!! $product->ProductInformation->sku_code !!}</td>--}}
{{--                                        <td>--}}
{{--                                            @if ($product->approval == 0)--}}
{{--                                                <a wire:click.prevent="approve({{ $product->id }}, {{ $product->requisition_id }})"--}}
{{--                                                    onclick="confirm('Are you sure you want to APPROVE This Requisition `{{ $product->ProductInformation->product_name }}`')||event.stopImmediatePropagation()"--}}
{{--                                                    type="button" class="btn btn-success btn-sm">Approve</a>--}}
{{--                                            @else--}}
{{--                                                <a wire:click.prevent="disapprove({{ $product->id }}, {{ $product->requisition_id }})"--}}
{{--                                                    onclick="confirm('Are you sure you want to DISAPPROVE This Requisition `{{ $product->ProductInformation->product_name }}`')||event.stopImmediatePropagation()"--}}
{{--                                                    type="button" class="btn btn-danger btn-sm">Disapprove</a>--}}
{{--                                            @endif--}}

{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
