<!-- resources/views/livewire/role-edit.blade.php -->

<div class="container-fluid">
    <div id="errorBox"></div>
    <form wire:submit.prevent="updateRole">
        <div class="card">
            <div class="card-body">
               <div class="row">
                <div class="form-group col-4">
                    <label for="name" class="form-label"> Name <span class="text-danger"> *</span></label>
                    <input wire:model.defer="name" type="text" name="name" class="form-control" placeholder="For e.g. Manager">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                  <div class="form-group col-4">
                     <label for="description" class="form-label"> Display Name <span class="text-danger"> *</span></label>
                     <input wire:model.defer="description" type="text" name="description" class="form-control">
                     @error('description')
                     <span class="text-danger">{{ $message }}</span>
                     @enderror
                  </div>
                  <div class="form-group col-4">
                     {!! Form::label('data_access_level', 'Data Access Level', ['class' => 'control-label']) !!}<span class="text-danger"> *</span>
                     {!! Form::select('data_access_level', [
                         'all' => 'All regions',
                         'regional' => 'Regional Data',
                         'subregional' => 'Subregional ',
                         'route' => 'Routes Data',
                     ], null, [
                         'class' => 'form-control',
                         'required' => 'required',
                         'id' => 'data_access_level_select',
                         'wire:model.defer' => 'data_access_level', // This line will set the selected value as wire:model.defer
                     ]) !!}
                  </div>

               </div>
            </div>
               <br/>
                <label for="name" class="form-label"> Assigned Platform Permissions <span class="text-danger"> *</span></label>
                <!--DataTable-->
                <div class="">
                    <table class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <input type="checkbox" wire:model="allPermission" name="all_permission">
                            </th>
                            <th>Initials</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $key=> $permission)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}">
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <div class="">
                        <button type="submit" class="btn  btn-md btn-primary"><i class="uil-update"></i> Update Role</button>
                        <a href="{{ url('/roles/create') }}" class="btn btn-md btn-warning"><i class="uil-backward"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
