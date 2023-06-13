<div>
    <div class="row">
       <div class="col-md-12 mb-1">

       </div>
       <div class="col-md-12">
          <div class="card">
             <div class="card-body">
                <table class="table table-bordered table-striped">
                   <thead>
                      <th>#</th>
                      <th>Sales Name</th>
                      <th>Status</th>
                      <th>Created On</th>
                      <th>Action</th>
                   </thead>
                   <tbody>
                      @foreach($requisitions as $count=>$requisition)
                         <tr>
                            <td>{!! $count+1 !!}</td>
                            <td>{!! $requisition->sales_person ?? '' !!}</td>
                            <td>@if ($requisition->status==="approved")
                              <button class="btn btn-success btn-sm">Approved</button>
                              @elseif ($requisition->status==="Waiting Approval")
                              <button class="btn btn-warning btn-sm">Waiting Approval</button>
                              @elseif ($requisition->status==="Disapproved")
                              <button class="btn btn-danger btn-sm">Disapproved</button>
                            @endif
                              </td>
                            <td>{!! date('F jS, Y', strtotime($requisition->created_at)) !!}</td>
                            <td>
                               <a href="{!! route('inventory.approve',[$requisition->id]) !!}" class="btn btn-sm" style="background-color: #B6121B;color:white">view</a>
                            </td>
                         </tr>
                      @endforeach
                   </tbody>
                </table>
             </div>
             <div class="mt-1">
                {{ $requisitions->links() }}
             </div>
          </div>
       </div>

       <!-- Modal -->

    </div>
 </div>
