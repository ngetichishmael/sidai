<div class="col-md-6">
    <div class="card card-inverse">
        <div class="card-body">
            <div class="card-body">
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Regions</th>
                           <th>Customers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regions as $key => $region)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $region->name }}</td>
                               <td>{{$customer_counts->where('region_id','=',$region->id)->count()}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $regions->links() }}
        </div>
    </div>
</div>
