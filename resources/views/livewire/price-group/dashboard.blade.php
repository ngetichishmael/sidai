<div class="col-md-6">
    <div class="card card-inverse">
        <div class="card-body">
            <div class="card-body">
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $key => $group)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $group->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $groups->links() }}
        </div>
    </div>
</div>
