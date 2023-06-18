<div>
    <div class="row mb-2">
        <div class="col-md-9">
            <label for="">Search</label>
            <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search ...">
            <!-- Button trigger modal -->
            <div class="mt-1">
                <a href="{{ route('leads.target.create') }}" type="button" class="btn" style="background-color: #B6121B;color:white">
                    New Target
                </a>
            </div>
        </div>
        <div class="col-md-3">
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
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <th>User Category</th>
                        <th>Target</th>
                        <th>Achieved</th>
                        <th>Dead Line</th>
                        <th>Count Down</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $lead->id }}</td>
                            <td>{{ $lead->User()->pluck('name')->implode('') }}</td>
                            <td>{{ number_format($lead->LeadsTarget) }}</td>
                            <td>{{ number_format($lead->AchievedLeadsTarget) }}</td>
                            <td>{{ $lead->Deadline }}</td>
                            <td>
                              @if ($today < $lead->Deadline)
                              <button type="button" class="btn btn-outline-success">
                                  <i data-feather="star" class="mr-25"></i>
                                  <span>
                                      @php
                                          $now = time();
                                          $deadline = strtotime($lead->Deadline);
                                          $datediff = $deadline-$now;
                                          echo round($datediff / (60 * 60 * 24));
                                      @endphp
                                  </span>
                              </button>
                          @else
                              <button type="button" class="btn btn-outline-danger">
                                 <i data-feather="alert-triangle" class="mr-25"></i>
                                 <span>
                                     @php
                                         $now = time();
                                         $deadline = strtotime($lead->Deadline);
                                         $datediff = $deadline-$now;
                                         echo round($datediff / (60 * 60 * 24));
                                     @endphp
                                 </span>
                             </button>
                          @endif
                            </td>
                            <td><a href="{{ route('leadstarget.edit',$lead->user_code) }}" class="btn btn-outline-info btn-sm">Edit</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"> No Leads Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
