<div>

            
    <div class="card">
                <h5 class="card-header"></h5>
                <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                    <div class="col-md-3 user_role">
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="search"></i></span>
                            </div>
                            <input  wire:model.debounce.300ms="search" type="text" id="fname-icon" class="form-control" name="fname-icon" placeholder="Search" />
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
           
    
            
                 <div class="col-md-6 d-flex justify-content-end">
                        <div class="demo-inline-spacing">
                            <a href="{{ route('leads.target.create') }}" class="btn btn-outline-secondary" style="background-color: brown;color:white">New Target</a>
                 
                        </div>
                    </div>
                 
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
                            <th>Success Ratio</th>
                            <th>Action</th>
    
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($targets as $key=>$target)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $target->name }}</td>
                                <td>{{ $target->TargetLead->LeadsTarget ?? 0 }}</td>
                                <td>{{ $target->TargetLead->AchievedLeadsTarget ?? 0 }}</td>
                                <td>{{ $target->TargetLead->Deadline ?? '' }}</td>
                                <td>
                                    {{ $this->getSuccessRatio($target->TargetLead->AchievedLeadsTarget ?? 0, $target->TargetLead->LeadsTarget ?? 0) }}%
                                </td>
                                <td><a href="{{ route('leadstarget.edit', $target->user_code) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                <a href="{{ route('leads.target.show', [
                                    'lead' => $target->user_code,
                                ]) }}" class="btn btn-outline-info btn-sm">View</a></td>
                                
                            </tr>
                        @empty
                            <tr>
                            <td colspan="7" align="center">No Targets Available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    