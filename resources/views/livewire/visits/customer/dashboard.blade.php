<div>
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
                <div class="btn-group">
                <button type="button" class="btn btn-icon btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" wire:loading.attr="disabled" >
                    <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="15" height="15">
                    Export
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item dropdown-toggle" data-toggle="collapse" href="#pdfOptions" role="button" aria-expanded="false" aria-controls="pdfOptions">
                    PDF
                </a>

                <div class="collapse" id="pdfOptions">
                <a class="dropdown-item" wire:click="exportPDF('today')">Today</a>
                            <a class="dropdown-item" wire:click.prevent="exportPDF('yesterday')">Yesterday</a>
                            <a class="dropdown-item" wire:click.prevent="exportPDF('this_week')">This Week</a>
                            <a class="dropdown-item" wire:click.prevent="exportPDF('this_month')">This Month</a>
                            <a class="dropdown-item" wire:click.prevent="exportPDF('this_year')">This Year</a>
                </div>

                <a class="dropdown-item dropdown-toggle" data-toggle="collapse" href="#csvOptions" role="button" aria-expanded="false" aria-controls="csvOptions">
                    CSV
                </a>
                <div class="collapse" id="csvOptions">
                <a class="dropdown-item" wire:click="exportCSV('today')">Today</a>
                            <a class="dropdown-item" wire:click.prevent="exportCSV('yesterday')">Yesterday</a>
                            <a class="dropdown-item" wire:click.prevent="exportCSV('this_week')">This Week</a>
                            <a class="dropdown-item" wire:click.prevent="exportCSV('this_month')">This Month</a>
                            <a class="dropdown-item" wire:click.prevent="exportCSV('this_year')">This Year</a>
                </div>

                <a class="dropdown-item dropdown-toggle" data-toggle="collapse" href="#excelOptions" role="button" aria-expanded="false" aria-controls="excelOptions">
                    EXCEL
                </a>
                <div class="collapse" id="excelOptions">
                <a class="dropdown-item" wire:click="exportExcel('today')">Today</a>
                            <a class="dropdown-item" wire:click.prevent="exportExcel('yesterday')">Yesterday</a>
                            <a class="dropdown-item" wire:click.prevent="exportExcel('this_week')">This Week</a>
                            <a class="dropdown-item" wire:click.prevent="exportExcel('this_month')">This Month</a>
                            <a class="dropdown-item" wire:click.prevent="exportExcel('this_year')">This Year</a>

                </div>
                </div>
                </div>

                <script>
                // Close the dropdown when an item is clicked
                $(".dropdown-item").on("click", function() {
                $(".btn-group").removeClass("show");
                });
                </script>
                </div>

            
        </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <div class="pt-0 card-datatable">
                <table class="table table-striped table-bordered zero-configuration table-responsive">
                    <thead>
                        <th width="1%">#</th>
                        <th>Region</th>
                        <th>User</th>
                        <th>Customer</th>
                        <th>Start/End Time</th>
                        <th>Duration</th>
                        <th>Date</th>

                    </thead>
                    <tbody>
                        @forelse ($visits as $count => $visit)
                            <tr>
                                <td>{!! $count + 1 !!}</td>
                                <td>{!! $visit->User->Region->name ?? '' !!}</td>
                                <td>{!! $visit->User->name ?? '' !!}</td>
                                <td>{{ $visit->Customer->customer_name ?? '' }}</td>
                                @if ($visit->stop_time === null)
                                    <td>
                                        <div class="badge badge-pill badge-secondary" style="color: white;background-color:brown">{{ \Carbon\Carbon::parse($visit->start_time)->format('h:i A') }}</div>
                                        <b> - </b>
                                        <span class="badge badge-pill badge-light-info mr-1">Visit Active</span>
                                    </td>
                                @else
                                    <td>
                                        <div class="badge badge-pill badge-secondary" style="color: white;background-color:brown">{{ \Carbon\Carbon::parse($visit->start_time)->format('h:i A') }}</div>
                                        <b> - </b>
                                        <div class="badge badge-pill badge-secondary" style="color: white;background-color:brown">{{ \Carbon\Carbon::parse($visit->stop_time)->format('h:i A') }}</div>
                                    </td>
                                @endif
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
                                <td>{{ $visit->created_at->format('d-m-Y') }}</td>
                           
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; ">No visits found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-1">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<br>
