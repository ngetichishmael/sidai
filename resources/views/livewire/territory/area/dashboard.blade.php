<div>
    <div class="mb-1 row">
        <div class="col-md-4">
            <label for="">Search by route</label>
            <input type="text" wire:model="searchTerm" class="form-control"
                placeholder="Enter route name">
        </div>
       <div class="col-md-3"></div>
       <div class="col-md-3">
          <label for="">Items Per Page</label>
          <select wire:model="perPage" class="form-control">`
             <option value="15" selected>15</option>
             <option value="25">25</option>
             <option value="50">50</option>
             <option value="100">100</option>
          </select>
        </div>
    </div>

    <div class="col-md-12">
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
</div>
