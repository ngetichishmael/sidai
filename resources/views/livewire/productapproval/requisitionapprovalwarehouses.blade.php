<div>
   <div class="row mb-1">
      <div class="col-md-10">
         <label for="">Search</label>
         <input type="text" wire:model="search" class="form-control" placeholder="Enter name">
      </div>
      <div class="col-md-2">
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
         <table class="table table-striped table-bordered table-responsive" style="font-size: small">
            <thead>
            <th width="1%">#</th>
            <th>Name</th>
            <th>Region</th>
            <th>Requisitions Counts</th>
            <th width="50%">Action | View</th>
            </thead>
            <tbody>
            @foreach($warehouses as $count=>$warehouse)
                  <tr>
                     <td>{!! $count+1 !!}</td>
                     <td>{!! $warehouse->name !!}</td>
                     <td>{!! $warehouse->region->name ?? '' !!}</td>
                     <td>{!! $warehouse->approval_count ?? 0 !!}</td>
                     <td>
                        <a href="{!! route('inventory.approval',$warehouse->warehouse_code) !!}" class="btn btn-sm" style="background-color: #52b3dc;color:white">Waiting Approvals</a>
                        <a href="{!! route('inventory.approved',$warehouse->warehouse_code) !!}" class="btn btn-sm" style="background-color: #70b467;color:white">Approvals</a>
                        <a href="{!! route('inventory.unapproved',$warehouse->warehouse_code) !!}" class="btn btn-sm" style="background-color: #fc7d50;color:white">Disapprovals</a>
                     </td>
                  </tr>
            @endforeach
            </tbody>
         </table>
         @if ($warehouses instanceof \Illuminate\Pagination\AbstractPaginator)
            {!! $warehouses->links() !!}
         @endif
      </div>
   </div>
</div>
@section('scripts')

@endsection
