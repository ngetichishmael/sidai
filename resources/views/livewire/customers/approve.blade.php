<div>
<div style="font-weight: lighter">
    <div class="mb-1 row">
        <div class="col-md-10">
            <label for="">Search by name, route, region</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name, email address or phone number">
        </div>
        <div class="col-md-2">
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
            <form method="POST" action="{{ route('customer.handleApproval') }}">
                @csrf
               @method('POST')
            <div class="card-datatable table-responsive font-small-3">
                <table class="table table-striped table-bordered font-small-2">
                    <thead>
                    <th width="1%">#</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Region</th>
                    <th>Sub-region</th>
                    <th>Route</th>
                    <th>Created By</th>
                    <th>Date</th>
                    <th width="5%">Action</th>
                    <th width="5%">Select</th>
                    </thead>
                    <tbody>
                        @forelse($contacts as $count => $contact)
                            <td>{!! $count + 1 !!}</td>
                            <td>
                                {!! $contact->customer_name !!}
                            </td>
                            <td>{!! $contact->customer_number !!}</td>

                            <td class="cell-fit">
                                {!! $contact->region_name ?? ($contact->Region->name ?? '') !!}
                            </td>
                            <td class="cell-fit">{!! $contact->subregion_name ?? '' !!}</td>
                            <td class="cell-fit">{!! $contact->area_name ?? '' !!}</td>
                            <td>
                                {!! $this->Creator($contact->id) ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at->format('d/m/Y') ?? '' !!}
                            </td>
                            <td>
                               <a href="{{ route('customer.editapprove', $contact->customer_id,$in="approve") }}" type="button" id="approve" class="dropdown-item btn btn-sm" style="color: #6df16d;">&nbsp;Edit</a>
                            </td>

                            @if ($contact->approval === "Approved")
                                  <td style="color: green">Approved</td>
                                  @elseif($contact->approval === "waiting_approval")
                                  <td>
                                       <input type="checkbox" name="selected_customers[]" value="{{ $contact->customer_id }}">
                                 </td>
                                  @endif

                            </tr>
                        @empty
                            <div>
                                <tr>
                                    <td colspan="10" class="text-center"> No Customer(s) Found ...</td>
                                </tr>
                            </div>
                        @endforelse
                    </tbody>
                </table>
                <button type="submit" class=" mt-1 pl-3 btn btn-primary" name="approve" value="approve">Approve Selected </button>
                <button type="submit" class=" mt-1 pl-3 btn btn-danger" name="disapprove" value="disapprove">Disapprove Selected </button>
            </div>
                </form>
                <div class="mt-1">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
