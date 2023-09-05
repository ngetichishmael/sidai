<div class="d-inline-block">

   <!-- Modal -->
   <div class="text-left modal fade modal-success" id="success" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel110" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="myModalLabel110">Selected Reassignment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form wire:submit.prevent="SelectedNotify()">
                      <div class="modal-body">

                          <br>
                          <div class="col-12">
                              <div class="form-group">
                                <label for="first-name-vertical">Title</label>
                                <input
                                wire:model.defer="title"
                                  type="text"
                                  id="first-name-vertical"
                                  class="form-control"
                                  name="fname"
                                  placeholder="Title"
                                  required
                                />
                              </div>
                            </div>

                          <br>
                          <p class="card-text mb-2">Body</p>
                          <div class="row">
                              <div class="col-12">
                                  <div class="form-label-group">
                                      <textarea wire:model.defer="body" class="form-control" id="label-textarea" rows="3" placeholder="Description for the Message" required></textarea>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-success" >Notify</button>
                      </div>
                  </form>
              </div>
           </div>
       </div>
   </div>
</div>

<div class="d-inline-block">
   <!-- Modal -->
   <div
     class="text-left modal fade modal-danger"
     id="danger"
     tabindex="-1"
     role="dialog"
     aria-labelledby="myModalLabel120"
     aria-hidden="true"
   >
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="myModalLabel120">Massive Reassignment</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">
            <div class="modal-body">
               <form wire:submit.prevent="MassiveNotify()">
                   <div class="modal-body">

                       <br>
                       <div class="col-12">
                           <div class="form-group">
                             <label for="first-name-vertical">Title</label>
                             <input
                             wire:model.defer="title"
                               type="text"
                               id="first-name-vertical"
                               class="form-control"
                               name="fname"
                               placeholder="Title"
                               required
                             />
                           </div>
                         </div>

                       <br>
                       <p class="card-text mb-2">Body</p>
                       <div class="row">
                           <div class="col-12">
                               <div class="form-label-group">
                                   <textarea wire:model.defer="body" class="form-control" id="label-textarea" rows="3" placeholder="Description for the Message" required></textarea>
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="submit" class="btn btn-success" >Notify</button>
                   </div>
               </form>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
