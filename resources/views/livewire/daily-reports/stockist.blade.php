<div>
      <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
         <div class="col-md-2">
            <button type="button" class="btn btn-icon btn-outline-success" wire:click="export"
                    wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
               Export
            </button>
         </div>
      </div>
      <div class="mb-1 row">
         <div class="col-md-10">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control" placeholder="Search by Customer Name, sales person name">
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
            <div class="card-datatable table-responsive">
               <table class="table table-striped table-bordered">
                  <thead>
                  <th width="1%">#</th>
                  <th>Sales Associate</th>
                  <th>Customer Name</th>
                  <th>Location</th>
                  <th>Phone Contact</th>
                  <th>Sales Generated(Amount)</th>
                  <th>General Coments</th>
                  </thead>
                  <tbody>
                  @forelse ($visits as $count => $visit)
                     <tr>
                        @php
                           $checkingData = $this->getChecking($visit->code);
                        @endphp
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $visit->name !!}</td>
                        <td>{!! $visit->customer_name !!}</td>
                        <td>{!! $visit->address !!}</td>
                        <td>{!! $visit->phone_number !!}</td>
                        <td>{!! $visit->amount ?? 0.00 !!}</td>
                     </tr>
                     <tr id="details{{ $visit->code }}" class="collapse">
                        <td colspan="8">
                           <table class="table table-bordered">
                              <tr>
                                 <td>Interested in New Order:</td>
                                 <td>{{ $checkingData['customer_ordered'] ?? 'N/A' }}</td>
                              </tr>
                              <tr>
                                 <td>Did outlet already have Stock ?</td>
                                 <td>{{ $checkingData['outlet_has_stock'] ?? 'N/A' }}</td>
                              </tr>
                              <tr>
                                 <td>Who is our potential competitors?</td>
                                 <td>{{ $checkingData['competitor_supplier'] ?? 'N/A' }}</td>
                              </tr>
                              <tr>
                                 <td>Which products have the highest sale?</td>
                                 <td>
                                    @if (is_array($checkingData['highest_sale_products'] ?? null))
                                       {{ implode(', ', $checkingData['highest_sale_products']) }}
                                    @else
                                       N/A
                                    @endif
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>

                  @empty
                     <tr>
                        <td colspan="8" style="text-align: center;">No Record found.</td>
                     </tr>
                  @endforelse
                  </tbody>
               </table>

               <div class="mt-1">
                  {{ $visits->links() }}
               </div>
            </div>
         </div>
      </div>
   </div>
