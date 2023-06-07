<div class="col-md-6">
      <div class="card card-inverse">
         <div class="card-body">
            <div class="card-body">
               <table id="data-table-default" class="table table-striped table-bordered">
                  <thead>
                     <tr>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th width="14%">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($brands as $key=>$all)
                        <tr>
                           <td>{{  $key+1  }}</td>
                           <td>{{  $all->name  }}</td>
                           <td>
                              <div class="d-flex" style="gap: 20px;">
                                 <a href="{{ route('product.brand.edit', $all->id) }}" class="btn btn-sm" style="background-color: #B6121B;color:white">
                                    Edit
                                    </a>
                                 <a href="{{  route('product.brand.destroy', $all->id)  }}" class="btn btn-sm delete" style="background-color: #B6121B;color:white">
                                    DELETE
                                 </a>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            {{ $brands->links()  }}
         </div>
      </div>
   </div>
