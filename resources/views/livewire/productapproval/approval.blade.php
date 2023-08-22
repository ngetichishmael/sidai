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
                            <th>Sales Person's Name</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($requisitions as $count => $requisition)
                                <tr>
                                    <td>{!! $count + 1 !!}</td>
                                    <td>{{ $requisition->user->name ?? '' }}</td>
{{--                                    <td>{{ $requisition->warehouse->name ?? '' }}</td>--}}
                                    @if (isset($requisition->requisition_products_count, $requisition->approved_requisition_products_count) &&
                                            $requisition->requisition_products_count > 0)
                                        @php
                                            $percentage = ($requisition->approved_requisition_products_count / $requisition->requisition_products_count) * 100;
                                            $data = $requisition->approved_requisition_products_count . '/' . $requisition->requisition_products_count;
                                        @endphp

                                        @if ($requisition->requisition_products_count === $requisition->approved_requisition_products_count)
                                            <td style="color: #78be6f">{{ $data }} Approved</td>
                                        @elseif ($percentage >= 75)
                                            <td style="color: #78be6f">{{ $data }} High Approval</td>
                                        @elseif ($percentage >= 50)
                                            <td style="color: #f5b747">{{ $data }} Moderate Approval</td>
                                        @elseif ($percentage > 0)
                                            <td style="color: #fd6b37">{{ $data }} Low Approval</td>
                                        @else
                                            <td style="color: #B6121B">{{ $data }}</td>
                                        @endif
                                    @else
                                        <td style="color: #B6121B">Data Unavailable</td>
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
