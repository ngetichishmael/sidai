<div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Number</th>
                <th>Address</th>
                <th>Zone/Region</th>
                <th>Route</th>
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
                    <td>
                        {{ $contact->Area->name ?? '' }}
                    </td>
                    <td>
                         {!! $contact->Creator->name ?? '' !!}
                     </td>
                     <td>
                         {!! $contact->created_at ? $contact->created_at->format('Y-m-d h:i A') : '' !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <button wire:click="export" class="btn btn-primary">Export CSV</button>
    </div>
</div>
