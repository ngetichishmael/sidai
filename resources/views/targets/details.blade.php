
@extends('layouts.app')
{{-- page header --}}
@section('title','Targets details')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Targets | History</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>


   <div class="card card-default">
    <div class="card-body">
        <div class="card-datatable table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <th width="1%">#</th>
                    <th>User Name</th>
                    <th>User Type</th>
                    <th>Lead</th>
                    <th>Sales</th>
                    <th>Visit</th>
                    <th>Orders</th>

                    
                </thead>
                <tbody>
                    @forelse($results as $count => $result)
                        <td>{{ $count + 1 }}</td>
                        <td>{{ $result->user_name }}</td>
                        <td>{{ $result->user_type }}</td>
                        <td>
                            @if ($result->leads_target > 0)
                                {{ number_format(($result->leads_achieved / $result->leads_target) * 100, 2) }}%
                            @else
                                {{ "Not Set" }}
                            @endif
                        </td>
                        <td>
                            @if ($result->sales_target > 0)
                                {{ number_format(($result->sales_achieved / $result->sales_target) * 100, 2) }}%
                            @else
                                {{ "Not Set" }}
                            @endif
                        </td>
                        <td>
                            @if ($result->visits_target > 0)
                                {{ number_format(($result->visits_achieved / $result->visits_target) * 100, 2) }}%
                            @else
                                {{ "Not Set" }}
                            @endif
                        </td>
                        <td>
                            @if ($result->orders_target > 0)
                                {{ number_format(($result->orders_target / $result->orders_target) * 100, 2) }}%
                            @else
                                {{ "Not Set" }}
                            @endif
                        </td>
                        {{-- <td>{{ $result->created_at->format('F j, Y') }}</td> --}}
                        </tr>
                    @empty
                        <div>
                            <tr>
                                <td colspan="10" class="text-center"> No product(s) Found ...</td>
                            </tr>
                        </div>
                    @endforelse
                </tbody>
            </table>
            {{-- @if (!empty($results))
                <div>
                    {{ $results->links() }}
                </div>
            @endif --}}

        </div>
    </div>
</div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
    
   
