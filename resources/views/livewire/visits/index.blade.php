<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
            data-toggle="tooltip" data-placement="top" title="Export Excel">
            <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
        </button>
    </div>
</div>

<br>
<div class="row">
    @include('partials.stickymenu')

    <div class="col-md-8">
        <div class="card card-default">
            <div class="card-body">
                <div class="card-datatable table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>User Type</th>
                                <th>Visits</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($visits as $key => $visit)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $visit->name ?? '' }}</td>
                                    <td>{{ $visit->account_type }}</td>
                                    <td>{{ $visit->checkings_count }}</td>
                                    <td><a href="" class="btn btn-sm"
                                            style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- <div class="mt-1">{!! $suppliers->links() !!}</div> --}}
            </div>
        </div>
    </div>
</div>
