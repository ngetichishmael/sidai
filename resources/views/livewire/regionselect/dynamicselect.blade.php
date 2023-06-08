<div>
    <div class="col-md-12 col-12">
        <div class="form-group">
    <label for="region_id">Region:</label>
    <select wire:model="region_id" id="region_id" class="form-control select2"
            name="region_id" required>
       <option value="">Select a region</option>
       @foreach($regions as $region)
          <option value="{{ $region->id }}">{{ $region->name }}</option>
       @endforeach
    </select>
        </div>
            </div>
    <br/>
    <div class="col-md-12 col-12">
       <div class="form-group">
    <label for="subregion_id">Subregion:</label>
    <select wire:model="subregion_id" id="subregion_id" class="form-control select2"
            name="subregion_id" >
       <option value="">Select a subregion</option>
       @foreach($subregions as $subregion)
          <option value="{{ $subregion->id }}">{{ $subregion->name }}</option>
       @endforeach
    </select>
 </div>
 </div>
    </div>
 <br/>
