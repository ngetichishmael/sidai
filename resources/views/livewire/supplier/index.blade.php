<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="">User Category</label>
        <select wire:model="" class="form-control">`
            <option value="" selected>select</option>
            <option value=""></option>

        </select>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
            data-toggle="tooltip" data-placement="top" title="Export Excel">
            <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
        </button>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <label for="">Status</label>
        <select wire:model="" class="form-control">
            <option value="" selected>select</option>
            <option value=""></option>

        </select>
    </div>
</div>
<br>
<div class="row">
    @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-default">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Quantity Of Inventory</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $key => $supplier)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->orders_count }}</td>
                                <td><a href="{{ route('supplierDetailed.reports', [
                                    'id' => $supplier->id,
                                ]) }}"
                                        class="btn btn-sm"
                                        style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                <div class="mt-1">{!! $suppliers->links() !!}</div>
            </div>
        </div>
    </div>
