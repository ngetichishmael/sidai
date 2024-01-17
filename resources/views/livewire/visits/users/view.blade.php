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
            <input type="text" wire:model="search" class="form-control" placeholder="Search by Customer Name">
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
                        <th>Customer Name</th>
                        <th>Start Time/Stop time</th>
                        <th>Duration</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        @foreach ($visits as $count => $visit)
                            <td>{!! $count + 1 !!}</td>
                            <td>{!! $visit->name !!}</td>
                            <td>{!! $visit->customer_name !!} </td>
                            <td class="cell-fit">
                                <div class="badge badge-pill badge-secondary" style="color: white; background-color:brown">{{ $visit->start_time ?? '' }}
                                </div>
                                <b> -</b>
                                <div class="badge badge-pill badge-secondary" style="color: white; background-color:brown">{{ $visit->stop_time ?? '' }}</div>
                            </td>
                            
                            <td>
                                @if (isset($visit->stop_time))
                                    @php
                                        $start = \Carbon\Carbon::parse($visit->start_time);
                                        $stop = \Carbon\Carbon::parse($visit->stop_time);
                                        $durationInSeconds = $start->diffInSeconds($stop);
                                    @endphp

                                    @if ($durationInSeconds < 60)
                                        <div class="badge badge-pill badge-dark" style="color: white;background-color:brown">{{ $durationInSeconds }} secs</div>
                                    @elseif ($durationInSeconds < 3600)
                                        <div class="badge badge-pill badge-dark" style="color: white;background-color:brown">{{ floor($durationInSeconds / 60) }} mins</div>
                                    @else
                                        <div class="badge badge-pill badge-dark" style="color: white;background-color:brown">{{ floor($durationInSeconds / 3600) }} hrs</div>
                                    @endif
                                @else
                                    <span class="badge badge-pill badge-light-info mr-1">Visit Active</span>
                                @endif
                            </td>
                            <td>{{ $visit->formatted_date }}</td>

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
