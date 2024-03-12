
@extends('layouts.app')
{{-- page header --}}
@section('title','Allocated items')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Ordered items</h2>
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
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Value</th>
                    <th>Allocated on</th>
                    
                </thead>
                <tbody>
                    @forelse($allocated as $count => $allocate)
                        <td>{{ $count + 1 }}</td>
                        <td>{{ $allocate->product_name }}</td>
                        <td>{{ $allocate->quantity }}</td>
                        <td>{{ $allocate->total_amount }}</td>
                        <td>{{ $allocate->created_at->format('F j, Y') }}</td>
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
            @if (!empty($allocated))
                <div>
                    {{ $allocated->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
    
   
