<!-- resources/views/livewire/role-show.blade.php -->

<div class="container-fluid">
    <div id="errorBox"></div>
    <div class="card">
        <div class="card-body">
           <div class="row">
            <h3>{{ $role->name }}    Role Details </h3>
           <p style="color: rgba(187,38,38,0.7)">Created By: <span>{{$role->CreatedBy->name ?? "N/A"}}</span> &nbsp; &nbsp; Updated By: <span>{{$role->UpdatedBy->name ?? "N/A"}}</span></p>
            <!-- Display other role details here if needed -->
           </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <label for="name" class="form-label ml-5"> Assigned Permissions</label>
            <!--DataTable-->
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable dtr-inline">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($role->permissions as $key => $permission)
                        <tr><td>{{ ++$key }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                <div class="mr-0">
                    <a href="{{ url('/roles/create') }}" class="btn btn-md btn-warning"><i class="uil-backward"></i> Back</a>
                </div>
            </div>
        </div>

    </div>


</div>

