<div class="col-md-6">
    <div class="card card-inverse">
        <div class="card-body">
            <div class="card-body">
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Sub-regions</th>
                            <th>Region</th>
                           <th>Customers</th>
{{--                           <th>Edit</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subregions as $key => $subregion)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subregion->name ?? '' }}</td>
                                <td>{{ $subregion->Region->name ?? '' }}</td>
                               <td>{{$customer_counts->where('subregion_id','=',$subregion->id)->count()}}</td>
{{--                               <td> <a href="{{ route('subregions.edit', $subregion->id) }}" type="button" class="dropdown-item btn btn-sm" style="color: #6df16d;">&nbsp;Edit</a>--}}
                               </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $subregions->links() }}
        </div>
    </div>
</div>
