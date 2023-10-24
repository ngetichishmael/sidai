<div>
    <!-- Spinner while fetching data -->
    <div wire:loading wire:target="data">
        <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <style>
        /* public/css/app.css or in your preferred CSS file */

.spinner {
    border: 4px solid rgba(0, 0, 0, 0.3);
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

    </style>
    @if ($isLoading)
        <div class="spinner"></div>
    @endif

    <!-- Content when data is loaded -->
    <div wire:loading.remove wire:target="data">
        <div class="card">
            <h5 class="card-header"></h5>
            <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                <div class="col-md-3 user_role">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i data-feather="search"></i></span>
                        </div>
                        <input wire:model="search" type="text" id="fname-icon" class="form-control" name="fname-icon" placeholder="Search" />
                    </div>
                </div>
                <div class="col-md-2 user_role">
                    <div class="form-group">
                        <label for="selectSmall">Per Page</label>
                        <select wire:model="perPage" class="form-control form-control-sm" id="selectSmall">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- <div class="col-md-2">
                    <div class="form-group">
                        <label for="validationTooltip02">Month</label>
                        <input wire:model="selectedMonth" name="month" type="month" class="form-control" id="validationTooltip02" required />
                    </div>
                </div> -->

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="validationTooltip02">Start Date</label>
                        <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip02" required />
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="validationTooltip03">End Date</label>
                        <input wire:model="end" name="end" type="date" class="form-control" id="validationTooltip03" required />
                        @if ($end && $start && $end < $start)
                            <p class="text-danger">End date cannot be before the start date.</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-icon btn-outline-primary" wire:click="export"
                        wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel" width="25" height="15">
                        <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="15" height="15"
                            data-toggle="tooltip" data-placement="top" title="Export Excel">Export
                    </button>
                </div>


            </div>
        </div>

        <div class="card card-default">
            <div class="card-body">
                <div class="pt-0 card-datatable">
                    <table class="table table-striped table-bordered zero-configuration table-responsive">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th>Sales Associate</th>
                                <th>Visit Count</th>
                                <th>Last Visit</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($visits->count() > 0)
                                @foreach ($visits as $count => $visit)
                                    <tr>
                                        <td>{!! $count + 1 !!}</td>
                                        <td>{!! $visit->name !!}</td>
                                        <td>{!! $visit->visit_count !!} </td>
                                        <td>
                                            @if ($visit->last_visit_date)
                                                {{ \Carbon\Carbon::parse($visit->last_visit_date)->format('j M, Y') }}
                                                @if ($visit->last_visit_time)
                                                    <div class="badge badge-pill badge-secondary">{{ \Carbon\Carbon::parse($visit->last_visit_time)->format('h:i A') }} </div>
                                                @else
                                                    - N/A
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('UsersVisits.show', ['user' => $visit->user_code]) }}" class="btn btn btn-sm" style="background-color: brown; color:white" >View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" style="text-align: center; ">No record found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-1">
                        {{ $visits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
