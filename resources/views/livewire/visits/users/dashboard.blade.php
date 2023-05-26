<div>
    <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="validationTooltip01">Start Date</label>
                <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="validationTooltip01">End Date</label>
                <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
        </div>
        
        <div class="col-md-2">
            <button type="button" class="btn btn-icon btn-outline-success" wire:click="export"
                wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
               Export
            </button>
        </div>
    </div>
    <div class="mb-1 row">
        <div class="col-md-10">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control" placeholder="Search by User or Region">
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
                        <th>Sales Associate</th>
                        <th>Visit Count</th>
                        <th>Total Time Spent</th>
                        <th>Total Trading Time</th>
                        <th>View</th>
                    </thead>
                    <tbody>
                        @foreach ($visits as $count => $visit)
                            <tr>
                                <td>{!! $count + 1 !!}</td>
                                <td>{!! $visit->name !!}</td>
                                <td>{!! $visit->visit_count !!} </td>
                                <td> {{ $visit->average_time }}</td>
                                <td>{{ $visit->total_time_spent }}</td>
                                <td>
                                    <a href="{{ route('UsersVisits.show', ['user' => $visit->user_code]) }}"
                                        class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-1">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
