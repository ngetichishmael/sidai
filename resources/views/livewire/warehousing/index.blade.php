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
                <th>Sub Region</th>
                @if(Auth::user()->account_type == 'Admin') <th>Manager's Name</th>@endif
                <th>Products Counts</th>
                <th>Action</th>
             </thead>
             <tbody>
                @foreach($warehouses as $count=>$warehouse)
                   @if(Auth::user()->account_type == 'Manager' && $warehouse->manager == Auth::user()->user_code)
                   <tr>
                      <td>{!! $count+1 !!}</td>
                      <td>{!! $warehouse->name !!}</td>
                      <td>{!! $warehouse->Region->Subregion->name ?? '' !!}</td>
                     <td>{!! $warehouse->manager ?? 'NA' !!}</td>
                      <td>{!! $warehouse->product_information_count !!}</td>
                      <td>
 {{--                        <a href="{!! route('warehousing.edit',$warehouse->warehouse_code) !!}" class="btn btn-primary btn-sm">Edit</a>--}}
                         <a href="{!! route('warehousing.products',$warehouse->warehouse_code) !!}" class="btn btn-primary btn-sm">Inventory</a>
                      </td>
                   </tr>
                   @elseif(Auth::user()->account_type == 'Admin')
                      <tr>
                         <td>{!! $count+1 !!}</td>
                         <td>{!! $warehouse->name !!}</td>
                         <td>{!! $warehouse->Region->name??''!!}</td>
                         <td>{!! $warehouse->Region->Subregion->name ?? '' !!}</td>
                         <td>{!! $warehouse->manager ?? 'NA' !!}</td>
                         <td>{!! $warehouse->product_information_count !!}</td>
                         <td>
                           <div class="d-flex">
                            <a href="{!! route('warehousing.edit',$warehouse->warehouse_code) !!}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{!! route('warehousing.products',$warehouse->warehouse_code) !!}" class="btn btn-success btn-sm">Inventory</a>
                           </div>
                         </td>
                      </tr>
                   @endif
                @endforeach
             </tbody>
          </table>
          {!! $warehouses->links() !!}
       </div>
    </div>
 </div>
 @section('scripts')
 
 @endsection
 