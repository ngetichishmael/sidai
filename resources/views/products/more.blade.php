
    <div class="card card-default">
        <div class="card-body">
            <div class="card-datatable table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">#</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Allocated on</th>
                        
                    </thead>
                    <tbody>
                        @forelse($allocated as $count => $allocate)
                            <td>{{ $count + 1 }}</td>
                            <td>{{ $allocate->product_name }}</td>
                            <td>{{ $allocate->quantity }}</td>
                            <td>{{ $allocate->created_at }}</td>
                            </tr>
                        @empty
                            <div>
                                <tr>
                                    <td colspan="10" class="text-center"> No product(s) Found ...</td>
                                </tr>
                            </div>
                        @endforelse
                    </tbody>
                </table>
                {{-- @if (!empty($allocated))
                    <div>
                        {{ $allocated->links() }}
                    </div>
                @endif --}}

            </div>
        </div>
    </div>
