<div>
   <div class="row mb-1">
      <div class="col-md-9">
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
         <table class="table table-striped table-bordered" style="font-size: small">
            <thead>
            <tr>
               <th width="1%">#</th>
               <th>Created By</th>
               <th>Restocked By</th>
               <th>Quantity</th>
               <th>Date</th>
               <th>time</th>
            </tr>
            </thead>
            <tbody>
            @if(empty($restockings))
               <span class="col-span-5">
                  No Restocking History Found for this product item...
               </span>
            @else
            @foreach($restockings as $key => $restocking)
               <tr>

                  <td>{!! $key + 1 !!}</td>
                  <td>{{ optional($restocking->addedBy)->name}}</td>
                  <td>{{ optional($restocking->restockedBy)->name}}</td>
                  <td>{{$restocking->restocked_quantity}}</td>
                  <td>{{ $restocking->updated_at->format('d/m/Y') }}</td>
                  <td>{{ $restocking->updated_at->format('H:i:s') }}</td>
               </tr>
            @endforeach
            @endif
            </tbody>
         </table>
         {!! $restockings->links() !!}
      </div>
   </div>
</div>
