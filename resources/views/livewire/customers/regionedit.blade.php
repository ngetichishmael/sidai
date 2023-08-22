<div class="col-md-6 col-12">
    <label>Region</label>
    <select wire:model='region' class="form-control" name="region">
        <option value="">Region</option>
        @foreach ($regions as $region)
            <option value="{{ $region->id }}">{{ $region->name }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-6 col-12">
    <label>Sub Region</label>
    <select wire:model='subregions'class="form-control" name="subregion">
        <option value="">Sub Region</option>
        @forelse ($subregions as $region)
            <option value="{{ $region->id }}">{{ $region->name }}</option>
        @empty
            <option value="1">Sub-Region</option>
        @endforelse
    </select>
</div>
<div class="col-md-6 col-12">
    <label>Route</label>
    <select class="form-control select select2" name="route">
        <option value="">Route</option>
        @forelse ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
        @empty
            <option value="1">Route</option>
        @endforelse
    </select>
</div>
