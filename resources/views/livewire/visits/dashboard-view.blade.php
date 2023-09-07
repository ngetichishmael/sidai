<div>
<div class="card">
            <h5 class="card-header"></h5>
            <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                <div class="col-md-4 user_role">
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
               <label for="validationTooltip01">Start Date</label>
               <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
                   placeholder="YYYY-MM-DD HH:MM" required />
           </div>
       </div>
       <div class="col-md-2">
           <div class="form-group">
               <label for="validationTooltip01">End Date</label>
               <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
                   placeholder="YYYY-MM-DD HH:MM" required />
           </div>
       </div>
             
            </div>
        </div>

        <div class="row mb-2 justify-content-end">
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
                            <td>{{ $target->VisitsTarget }}</td>
                            <td>{{ $target->AchievedVisitsTarget }}</td>
                            <td>{{ $target->Deadline }}</td>
                            <td>
                                @if ($today < $target->Deadline)
                                    <span class="btn btn-flat-success">Active</span>
                                @else
                                    <span class="btn btn-flat-danger">Expired</span>
                                @endif
                            </td>
                            <td>
                                {{ $this->getSuccessRatio($target->AchievedVisitsTarget, $target->VisitsTarget) }}%
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Targets Available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
