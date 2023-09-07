<div>
    <div class="row mb-2">
        <div class="col-md-3">
            <div class="form-group">
                <label for="validationTooltip01">Start Date</label>
                <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="validationTooltip01">End Date</label>
                <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
        </div>
        <div class="col-md-3">
            <label for="">Items Per</label>
            <select wire:model="perPage" class="form-control">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="">Time Frame</label>
            <select wire:model="timeFrame" class="form-control">
                <option value="quarter">Quarter</option>
                <option value="half_year">Half Year</option>
                <option value="year">Year</option>
            </select>
        </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <th>Sales Person</th>
                        <th>Target</th>
                        <th>Achieved</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Success Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($targets as $target)
                        <tr>
                            <td>{{ $target->id }}</td>
                            <td>{{ $target->User->name }}</td>
                            <td>{{ $target->SalesTarget ?? 0 }}</td>
                            <td>{{ $target->AchievedSalesTarget ?? 0 }}</td>
                            <td>{{ $target->Deadline ?? '' }}</td>
                            <td>
                                @if ($today < $target->Deadline)
                                    <span class="btn btn-flat-success">Active</span>
                                @else
                                    <span class="btn btn-flat-danger">Expired</span>
                                @endif
                            </td>
                            <td>
                                {{ $this->getSuccessRatio($target->AchievedSalesTarget ?? 0, $target->SalesTarget ?? 0) }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No Targets Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
