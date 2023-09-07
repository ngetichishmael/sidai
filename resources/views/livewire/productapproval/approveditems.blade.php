<div>
<div class="float-end">
   <a href="{{ URL::previous() }}" class="btn btn-md" style="background: rgb(255,128,85); color: white">Back</a>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="pt-0 card-datatable table-responsive">
                <div class="card">
                    <div class="card-header"> Approved Items</div>
                    <div class="card-body">
                          <table class="table table-bordered table-striped">
                            <thead>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>SKU Code</th>
                                <th>Requested Quantity</th>
                                <th>Allocate Quantity</th>
                                <th>Info</th>
                            </thead>
                            <tbody>
                            @foreach ($products as $count => $product)
                               <tr>
                                  <td>{!! $count + 1 !!}</td>
                                  <td>{!! $product->ProductInformation->product_name ?? '' !!}</td>
                                  <td>{!! $product->ProductInformation->sku_code ?? '' !!}</td>
                                  <td>{!! $product->allocated_quantity ?? '' !!}</td>
                                  <td>{!! $product->quantity ?? '' !!}</td>
                                  @if ($product->approval === 1)
                                  <td style="color: green">Approved</td>
                                  @else
                                     <td style="color: #fc7d50">Unapproved</td>
                                  @endif
                               </tr>
                            @endforeach
                            </tbody>
                          </table>
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
