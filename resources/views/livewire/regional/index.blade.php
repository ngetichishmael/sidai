<div class="row">
<div class="col-md-3">
   <label for="">Filter By Region</label>
   <select wire:model="" class="form-control">`
       <option value="" selected>select</option>
       @foreach ($regions as $region)
       <option value="">{{ $region->name }}</option>
       @endforeach
   </select>
</div>
<div class="col-md-3">
   <label for="">Filter By Region</label>
   <select wire:model="" class="form-control">`
       <option value="" selected>select</option>
       @foreach ($regions as $region)
       <option value="">{{ $region->name }}</option>
       @endforeach
   </select>
</div>
</div>
<br>
<div class="row">
   @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Region</th>
                       <th>No of Orders</th>
                       <th>No of Customers</th>
                       <th>No of Deliveries</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($regions as$count=> $region)
                  <tr>
                     <td>{{ $count+1 }}</td>
                     <td>{{ $region->name }}</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     {{-- <td><a href="{{ route('subregion.reports',['id'=>$region->id]) }}" class="btn sm" style="background-color: brown;color:white">View</a></td> --}}
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>