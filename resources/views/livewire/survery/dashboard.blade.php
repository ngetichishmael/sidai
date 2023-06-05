<div>
    <div class="row">
        <div class="col-md-12">
            <a href="{!! route('survey.create') !!}" class="btn btn-primary btn-sm mb-2">Add Survey</a>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered zero-configuration">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                {{-- <th width="6%"></th> --}}
                                <th>Title</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date CreateD</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveries as $count => $survery)
                                <tr class="odd gradeX">
                                    <td width="1%" class="f-s-600 text-inverse">{!! $count + 1 !!}</td>
                                    {{-- <td ><img src="{!! asset('survey/trivia/'.$survery->image)!!}" class="img-responsive"/></td> --}}
                                    <td>{!! $survery->title !!}</td>
                                    <td>
                                        {{ date('j M Y', strtotime($survery->start_date)) }}
                                    </td>
                                    <td>{{ date('j M Y', strtotime($survery->end_date)) }}</td>
                                    <td>{!! $survery->type !!}</td>
                                    <td>{!! $survery->status !!}</td>
                                    <td>{{ date('j M Y', strtotime($survery->updated_at)) }}</td>
                                    <td>

                                       <div class="dropdown" >
                                          <button class="btn btn-md btn-primary dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                             <i data-feather="settings"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                             <a href="{!! route('survey.show', $survery->code) !!}" type="button" class="dropdown-item btn btn-sm" style="color: #86f686;font-weight: bold"><i data-feather="edit"></i> &nbsp;Edit</a>
                                             <a href="{!! route('survey.edit', $survery->code) !!}" type="button" class="dropdown-item btn btn-sm" style="color: #a1e1f6; font-weight: bold"><i data-feather="eye"></i>&nbsp; View</a>
                                             <a href="{!! route('survey.delete', $survery->code) !!}" type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f; font-weight: bold"><i data-feather="delete"> </i> &nbsp; Delete</a>
                                          </div>
                                       </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $surveries->links() }}
            </div>
        </div>
    </div>
</div>
