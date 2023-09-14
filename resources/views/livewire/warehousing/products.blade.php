<div>
      <div class="row mb-1">
         <div class="col-md-9">
            <label for=""></label>
            <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search Product">
         </div>
{{--         @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM" || Auth::check() && (strtolower(Auth::user()->account_type == "shop-attendee")==0))--}}
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
            <table class="table table-striped table-bordered" style="font-size: small">
               <thead>
               <tr>
                  <th width="1%">#</th>
                  <th>Name</th>
                  <th>Wholesale Price</th>
                  <th>Distributor Price</th>
                  <th>Retail Price</th>
                  <th>Current Stock</th>
                  <th>Date</th>
                  <th>time</th>
{{--                  @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM")--}}
                     <th>Actions</th>
{{--                  @endif--}}
               </tr>
               </thead>
               <tbody>

               @foreach($products as $key => $product)
{{--                  @if((Auth::check() && Auth::user()->account_type == "RSM") || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "Admin" )--}}
                     <tr>
                        <td>{!! $key + 1 !!}</td>
                        <td>{!! $product->product_name !!}</td>

                        @if ($product->ProductPrice->buying_price ==  0 || 00)
                           <td>{{'Price Not set' }}</td>
                        @else
                           <td>{{number_format((float) $product->ProductPrice->buying_price)}}</td>
                        @endif
                        <td>
                           {{ number_format((float) $product->ProductPrice()->pluck('distributor_price')->implode('')) }}
                        </td>
                        <td>
                           {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
                        </td>
                        <td>{{ $product->Inventory()->pluck('current_stock')->implode('') }} </td>
                        <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                        <td>{{ $product->updated_at->format('H:i:s') }}</td>
                        <td>
                           <div class="dropdown" >
                              <button style="background-color: #B6121B;color:white" class="btn btn-md dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                 <i data-feather="settings"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                 <a href="{{ route('products.restock', $product->id) }}" type="button" class="dropdown-item btn btn-sm" style="color: #6df16d;font-weight: bold"><i data-feather="plus"></i> &nbsp;Restock</a>
                                 @if (Auth::user()->account_type == "Admin" || Auth::user()->account_type ==="NSM")
                                    <a href="{{ route('products.view', $product->id) }}" type="button" class="dropdown-item btn btn-sm" style="color: #7cc7e0; font-weight: bold"><i data-feather="plus"></i>&nbsp; Update Price</a>
                                 @endif
                                 @if (Auth::user()->account_type == "Admin" || Auth::user()->account_type ==="NSM")
                                    <a href="#" type="button" class="dropdown-item btn btn-sm" style="color: #7cc7e0; font-weight: bold"><i data-feather="plus"></i>&nbsp;Restock History</a>
                                 @endif

                              </div>
                           </div>


                        </td>
                     </tr>

               @endforeach

               </tbody>
            </table>
            {!! $products->links() !!}
         </div>
      </div>
</div>
