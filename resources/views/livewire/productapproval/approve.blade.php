<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pt-0 card-datatable table-responsive">
                <div class="card">
                    <div class="card-header"> Allocated Items</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>SKU Code</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($products as $count => $product)
                                    <tr>
                                        <td>{!! $count + 1 !!}</td>
                                        <td>{!! $product->ProductInformation->product_name !!}</td>
                                        <td>{!! $product->quantity !!}</td>
                                        <td>{!! $product->ProductInformation->sku_code !!}</td>
                                        <td>
                                                <button wire:click.prevent="approve()"
                                                    onclick="confirm('Are you sure you want to APPROVE `{{$product->ProductInformation->product_name  }}`')||event.stopImmediatePropagation()"
                                                    type="button" class="btn btn-success btn-sm">Approved</button>
                                        </td>
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
