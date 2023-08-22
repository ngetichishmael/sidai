<div>
    <div class="col-md-12 col-12">
        <div class="form-group">
    <label for="region_id">Route:</label>
    <select wire:model="route_id" id="route_id" class="form-control select2"
            name="name" required>
       <option value="">Select a route</option>
       @foreach($routes as $route)
          <option value="{{ $route->name }}">{{ $route->name }}</option>
       @endforeach
    </select>
        </div>
            </div>
    <br/>
    {{-- <p style="color:green">This route has {{ $customer_count }} Customers</p> --}}
    <div class="col-md-12 col-12" hidden>
       <div class="form-group">
    <label for="subregion_id">Route Customers:</label>
    <select wire:model="customer" id="customer_ids" class="form-control"
            name="customer_ids">
       @foreach($customers as $customer)
          <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
       @endforeach
    </select>
 </div>
 </div>
    </div>
 <br/>
