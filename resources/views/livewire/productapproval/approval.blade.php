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
                            @foreach ($requisitions as $count => $requisition)
                                <tr>
                                    <td>{!! $count + 1 !!}</td>
                                    <td>{!! $requisition->sales_person ?? '' !!}</td>
                                    @if ($requisition->status === 'approved')
                                        <td style="color: #78be6f"> Approved</td>
                                    @elseif ($requisition->status === 'Waiting Approval')
                                        <td style="color: #f5b747">Waiting Approval</td>
                                    @elseif ($requisition->status === 'Disapproved')
                                        <td style="color: #fd6b37">Disapproved</td>
                                    @endif

                                    <td>{!! date('F jS, Y', strtotime($requisition->created_at)) !!}</td>
                                    <td>
                                        <a href="{!! route('inventory.approve', [$requisition->id]) !!}" class="btn btn-sm"
                                            style="background-color: #B6121B;color:white">view</a>
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
