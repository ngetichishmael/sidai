<!-- resources/views/livewire/roles-management.blade.php -->

<div class="container-fluid">
    <div id="errorBox"></div>
    <form wire:submit.prevent="storeRole">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="form-label"> Name <span class="text-danger"> *</span></label>
                    <input wire:model.defer="name" type="text" name="name" class="form-control" placeholder="For e.g. Manager">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <label for="name" class="form-label"> Assign Permissions <span class="text-danger"> *</span></label>
                <!--DataTable-->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" wire:model="allPermission" name="all_permission">
                            </th>
                            <th>Name</th>
                            <th>Guard</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}">
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Role</button>
            </div>
        </div>
    </form>
</div>
