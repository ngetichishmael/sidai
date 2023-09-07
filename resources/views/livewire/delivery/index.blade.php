<div>
   <div class="col-xl-12 col-md-12 col-12">
      <div class="card">
         <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="fromDate">From:</label>
                  <input type="date" id="fromDate" wire:model="fromDate"
                         name="startDate" type="date" class="form-control" placeholder="YYYY-MM-DD HH:MM" required>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="validationTooltip01">End Date</label>
                  <input type="date" id="toDate" wire:model="toDate" name="endDate" type="date" class="form-control"
                         placeholder="YYYY-MM-DD HH:MM" required />
               </div>
            </div>
         </div>
      </div>
   </div>
    <div class="mb-1 row">
        <div class="col-md-10">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name,order number or sales person">
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
            <div class="card-datatable table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">#</th>
                        <th>Customer</th>
                        <th>OrderID</th>
                        <th>Sub-Region</th>
                        <th>Route</th>
                        <th>Sales Agents</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($deliveries as $count => $deliver)
                        <tr>
                            <td>{!! $count + 1 !!}</td>
                           <td title="{{ $deliver->Customer->customer_name ?? null }}">{{ Str::limit($deliver->Customer->customer_name ?? null, 20) }}</td>
                           <td>{!! $deliver->order_code ?? '' !!}</td>
                           <td title="{{ $deliver->Customer->Area->Subregion->name ?? null }}">
                                {{ Str::limit($deliver->Customer->Area->Subregion->name ?? null, 20) }}</td>
                            <td title="{{ $deliver->Customer->Area->Subregion->name ?? null }}">
                                {{ Str::limit($deliver->Customer->Area->name ?? null, 20) }}</td>
                            <td>{!! $deliver->User->name ?? '' !!}</td>
                           <td>{!! $deliver->updated_at ?? '' !!}</td>
                           <td class="{{ $deliver->delivery_status === 'Delivered' ? 'text-green' : ($deliver->delivery_status === 'Partial delivery' ? 'text-blue' : '') }}">
                              {{ $deliver->delivery_status }}</td>

{{--                            <td><a href="" class="badge {!! $deliver->delivery_status !!}"--}}
{{--                                    style="color: rgb(2, 66, 100);">{!! $deliver->delivery_status !!}</a></td>--}}
                            <td><a href="{!! route('delivery.details', $deliver->order_code, $deliver->User->name ?? '') !!}" class="btn btn-sm btn-success">View</a></td>
                        </tr>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="mt-1">{!! $deliveries->links() !!}</div>
            </div>
        </div>
    </div>
</div>
