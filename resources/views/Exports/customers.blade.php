<div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Number</th>
                <th>Address</th>
                <th>Zone/Region</th>
                <th>Route</th>
               <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->customer_name }}</td>
                    <td>{{ $contact->phone_number }}</td>
                    <td>{{ $contact->address }}</td>
                    <td>
                        @if ($contact->Area && $contact->Area->Subregion && $contact->Area->Subregion->Region)
                            {{ $contact->Area->Subregion->Region->name }}
                            @if ($contact->Area->Subregion->name)
                                , <br><i>{{ $contact->Area->Subregion->name }}</i>
                            @endif
                        @endif
                    </td>
                    <td>{{ $contact->Area->name ?? '' }}</td>
                   @php
                      $lastOrderDate = $contact->last_order_date ? \Carbon\Carbon::parse($contact->last_order_date) : null;
                                         $now = \Carbon\Carbon::now();
                                             if ($lastOrderDate !== null) {
                                                 $differenceInMonths = $lastOrderDate->diffInMonths($now);
                                             } else {
                                                 $differenceInMonths = null;
                                             }
                                         $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3);
                                         $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
                                         $createdAtDate = \Carbon\Carbon::parse($contact->created_at);
                                          $daysDifference = $oneMonthAgo->diffInDays($createdAtDate);
                   @endphp

                   @if ($lastOrderDate != null && $lastOrderDate->lessThanOrEqualTo($oneMonthAgo))
                      <td> Active </td>
                   @elseif ($lastOrderDate != null && ($daysDifference >= 30 && $daysDifference <= 90))
                      <td> Partially Inactive </td>
                   @elseif ($lastOrderDate != null && $daysDifference >= 90)
                      <td> Inactive </td>
                   @elseif ($lastOrderDate === null && $daysDifference <= 30)
                      <td> New </td>
                   @elseif ($lastOrderDate === null && $daysDifference > 30)
                      <td> New Inactive </td>
                   @else
                      <td> Inactive </td>
                   @endif
                    <td>{!! $contact->Creator->name ?? '' !!}</td>
                     <td>{!! $contact->created_at ? $contact->created_at->format('Y-m-d h:i A') : '' !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <button wire:click="export" class="btn btn-primary">Export CSV</button>
    </div>
</div>
