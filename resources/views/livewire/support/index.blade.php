
   <div>
      <div class="mb-2 row">
         <div class="col-md-9">
            <label for="">Search</label>
            <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="status, username, subject">
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
                  <th>User Name</th>
                  <th>Account Type</th>
{{--                  <th>Subject</th>--}}
                  <th>Status</th>
                  <th>Time</th>
                  <th>Read</th>

               </tr>
               </thead>

               <tbody>
               @foreach($tickets as $ticket)
                  <tr>
                     <td>{{ $ticket->user->name ?? "N/A" }}</td>
                     <td>{{ $ticket->user->account_type ?? "N/A" }}</td>
{{--                     <td>{{Str::limit( $ticket->subject, 40) }}</td>--}}
                     <td style="color: {{ $ticket->status == 'open' ? 'orangered' : 'green' }}">
                        Offline
                     </td>
                     <td>{{ $ticket->created_at->diffForHumans() ?? "N/A" }}</td>
                     <td style="color: {{ $ticket->read == 1 ? 'gray' : 'orangered' }}">
                     <center>
                        <a href="{!! route('support.show', ['id' => $ticket->id]) !!}" class="btn btn-sm">
                           <i style="color: #0C102A" data-feather="edit"></i></a>
                     </center>

                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
            <div class="mt-1">{!! $tickets->links() !!}</div>
         </div>
      </div>
   </div>
   <script src="{{ mix('js/app.js') }}"></script>
   @livewireScripts

