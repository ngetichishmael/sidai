<div>
   <table>
      <thead>
      <tr>
         <th>Name</th>
         <th>Phone</th>
         <th width="20%">Address</th>
         <th>Route/Town</th>
         <th>Subregion</th>
         <th>Region</th>
         <th>Status</th>
         <th>Added By</th>
         <th>Created At</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($contacts as $contact)
         <tr>
            <td>{{ $contact->customer_name }}</td>
            <td>{{ $contact->customer_number }}</td>
            <td>{{ $contact->address ??'' }}</td>
            <td> {!! $contact->area_name !!}</td>
            <td>{!! $contact->subregion_name !!}</td>
            <td>{!! $contact->region_name !!}</td>
            <td>
               @if ($contact->fstatus=="Active")
                  <span class="badge btn-outline-success">Active</span>
               @elseif ($contact->fstatus=="Partially Inactive")
                  <span class="badge btn-outline-info">Partially Inactive</span>
               @elseif ($contact->fstatus=="Inactive")
                  <span class="badge btn-outline-danger">Inactive</span>
               @elseif ($contact->fstatus=="New")
                  <span class="badge btn-outline-secondary">New</span>
               @elseif ($contact->fstatus=="New Inactive")
                  <span class="badge btn-outline-warning">New Inactive</span>
               @else
                  <span class="badge btn-outline-warning">Inactive</span>
               @endif
            </td>
            <td>{{ $contact->Creator->name ?? '' }}</td>
            <td>{!! $contact->created_at ? $contact->created_at->format('Y-m-d h:i A') : '' !!} </td>
         </tr>
      @endforeach
      </tbody>
   </table>
    <div class="mt-4">
        <button wire:click="export" class="btn btn-primary">Export CSV</button>
    </div>
</div>
