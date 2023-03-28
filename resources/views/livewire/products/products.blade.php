<div>
    <div class="mb-2 row">
        <div class="col-md-9">
            <label for="">Search</label>
            <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Enter Product name">
        </div>
        <div class="col-md-3">
            <label for="">Items Per</label>
            <select wire:model="perPage" class="form-control">`
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th width="10%">Retail Price</th>
                        <th width="13%">Wholesale Price</th>
                        <th width="13%">Distributor's Price</th>
                        <th width="12%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td>{!! $key + 1 !!}</td>
                            <td>{!! $product->product_name !!}</td>
                            <td>
                                ksh:
                                {{ number_format((float) $product->ProductPrice()->pluck('buying_price')->implode('')) }}
                            </td>
                            <td>
                                ksh:
                                {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
                            </td>
                            <td>
                                ksh:
                                {{ number_format((float) $product->ProductPrice()->pluck('default_price')->implode('')) }}
                            </td>

                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                    <span>Edit</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-1">{!! $products->links() !!}</div>
        </div>
    </div>
</div>
