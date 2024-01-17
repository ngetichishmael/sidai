<div>
   <div class="mb-2 row">
      <div class="col-md-6 col-sm-6">
         <label for="">Search</label>
         <input wire:model="search" type="text" class="form-control" placeholder="username, Activity, Action">
      </div>


      <div class="col-md-3 col-sm-4">
         <label for="">Items Per</label>
         <select wire:model="perPage" class="form-control">`
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
         </select>
      </div>
      <div class="col-md-3">
         <label for="">Export:</label>
         <div class="dropdown">
            <button style="background-color: #B6121B;color:white"
                    class="mr-2 btn btn-md dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-trigger="click" aria-haspopup="true" aria-expanded="false"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
               <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="15" height="15">
               Export
            </button>
            <div class="dropdown-menu dropdown-menu-left">
               <a class="dropdown-item" wire:click="exportExcel" id="exportExcelBtn">Excel</a>
               <a class="dropdown-item"  wire:click="exportCSV" id="exportCsvBtn"> CSV</a>
               <a class="dropdown-item"  wire:click="exportPDF" id="exportPdfBtn">PDF</a>
            </div>
         </div>
      </div>
   </div>
   <div class="mb-2 row">
      <d class="col-md-2 col-sm-1 col-lg-4">

      </d>
      <div class="col-md-2 col-sm-4">
                    <div class="form-group">
                        <label for="selectSmall">Sort</label>
                        <select class="form-control form-control-sm" id="selectSmall" wire:model="sortAsc">
                            <option value="1">Newest to Oldest</option>
                            <option value="0">Oldest to Newest</option>
                        </select>
                    </div>
                </div>
      <div class="col-md-3 col-sm-4">
         <label for="">Start Date</label>
         <input wire:model="startDate" type="date" class="form-control">
      </div>
      <div class="col-md-3 col-sm-4">
         <label for="">End Date</label>
         <input wire:model="endDate" type="date" class="form-control">
      </div>
   </div>
       <div class="card card-default">
          <div class="card-body">
             <table class="table table-striped table-bordered" style="font-size: small">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Section</th>
                                <th>User Name</th>
                                <th>Activity</th>
                               <th>Date</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($activities as $key => $activity)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $activity->section }}</td>
                                    <td>{{ $activity->user->name ?? 'NA'}}</td>
                                   <td>{{ Str::limit($activity->activity, 20) }}</td>
                                    <td>{!! $activity->created_at ?? now() !!}</td>
                                    <td > <a href="{{ route('activity.show', $activity->id) }}" style="color:#629be7">
                                            <i data-feather="eye" ></i>&nbsp; View </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center;"> No Record Found </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-1">{{ $activities->links() }}
                    </div>
          </div>
       </div>
</div>
