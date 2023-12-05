
<div class="card card-default">
   <div class="card-body">
      <table class="table table-striped table-bordered">
         <thead>
         <tr>
            <th width="1%">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone number</th>
            <th>Date addded</th>
            @if(!$type=="Shop-Attendee")
               <th width="18%">Action</th>
            @endif
         </tr>
         </thead>
         <tbody>
         @foreach ($suppliers as $count => $supplier)
            <tr {{-- class="success" --}}>
               <td>{!! $count + 1 !!}</td>
               <td>{!! $supplier->name !!}</td>
               <td>{!! $supplier->email !!}</td>
               <td>{!! $supplier->phone_number !!}</td>
               <td>{!! date('d F, Y', strtotime($supplier->created_at)) !!}</td>
               @if(!$type=="Shop-Attendee")
               <td>
                  <div class="d-flex" style="gap: 20px">
                     <a href="{!! route('supplier.activate', $supplier->id) !!}" class="btn btn-sm btn-primary">
                        <span>Activate</span>
                     </a>
                  </div>
               </td>
               @endif
            </tr>
         @endforeach
         @if(empty($supplier))
            <div>
               <tr>
                  <td colspan="6" class="align-content-center"> No Archived Distributor Found ...</td>
               </tr>
            </div>
         @endif
         </tbody>
      </table>

      <div class="mt-1">{!! $suppliers->links() !!}</div>
   </div>
</div>
