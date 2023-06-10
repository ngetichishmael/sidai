<div>
    <div class="col-md-12 col-12">
        <div class="form-group">
    <label for="region_id">Route:</label>
    <select wire:model="route_id" id="route_id" class="form-control"
            name="route_id" required>
       <option value="">Select a route</option>
       @foreach($routes as $route)
          <option value="{{ $route->id }}">{{ $route->name }}</option>
       @endforeach
    </select>
        </div>
            </div>
    <br/>
    <div class="col-md-12 col-12" hidden>
       <div class="form-group">
    <label for="subregion_id">Route Customers:</label>
    <select id="customer_ids" class="form-control"
            name="customer_ids" >
       <option value="">Select a customer</option>
       @foreach($customers as $customer)
          <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
       @endforeach
    </select>
 </div>
 </div>
    </div>
 <br/>