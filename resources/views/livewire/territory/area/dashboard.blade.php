<div class="col-md-8">
    <div class="card card-inverse">
        <div class="card-body">
            <div class="card-body">
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Route</th>
                            <th>Sub Region</th>
                            <th>Region</th>
                           <th>Customers</th>
{{--                           <th>Actions</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($areas as $key => $area)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->Subregion->name }}</td>
                                <td>{{ $area->Subregion->Region->name }}</td>
                               <td>{{$customer_counts->where('route','=',$area->id)->count()}}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $areas->links() }}
        </div>
    </div>
</div>
