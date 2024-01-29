<div class="card mt-3">
   <div class="card-body">
      <table class="table">
         <thead>
            <tr>
               <th>#</th>
               <th>Sales Associate</th>
               <th>Visit Count</th>
               <th>Last Visit</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            @forelse ($visits as $visit)
            <tr>
               <td>{{ $loop->iteration }}</td>
               <td>{{ $visit->name }}</td>
               <td>{{ $visit->visit_count }}</td>
               <td>{{ $visit->last_visit_date }}</td>
               <td>
                  <a href="{{ route('UsersVisits.show', ['user' => $visit->user_code]) }}" class="btn btn btn-sm" style="background-color: #1877F2; color:white" >View</a>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="5">No visits found.</td>
            </tr>
            @endforelse
         </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-2">
         {{ $visits->links() }}
      </div>
   </div>
</div>
