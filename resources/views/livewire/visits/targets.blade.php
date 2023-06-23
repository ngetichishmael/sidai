
<div>
   <div class="col-lg-12 col-12">
       <div class="card">
           <h5 class="card-header">Assign Target</h5>
       </div>
       <div class="card">
           <div class="card-body p-0">
               <div>
                   <table class="table">
                       <thead class="thead-light">
                           <tr>
                               <th>Account Type</th>
                               <th>Sales Force</th>
                               <th>Target</th>
                               <th>Deadline</th>
                               <th>Action</th>
                           </tr>
                       </thead>
                       <tbody>
                                @foreach ($Targets as $index => $target)
                                <tr class="col-12">
                                            <td>
                                                <label for="fp-date-time">Account type</label>
                                                <select name="account_type" class="form-control select" id="account_type" required wire:model="selectedAccountType">
                                                    <option value=""> -- Choose User Type -- </option>
                                                    @foreach ($account_types as $account)
                                                    <option value="{!! $account->account_type !!}">{!! $account->account_type !!}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <label for="fp-date-time">Sales Person</label>
                                                <select wire:model.prevent="Targets.{{ $index }}.primarykey" id="user" class="form-control select2 @error('targets.{{ $index }}.primarykey') border border-danger @enderror">
                                                    <option value=""> -- Choose Sales Person -- </option>
                                                    <option value="ALL">ALL</option>
                                                    @foreach ($users as $user)
                                                    <option value="{!! $user->user_code !!}">{!! $user->name !!}</option>
                                                    @endforeach
                                                </select>
                                                @error('targets.{{ $index }}.primarykey')
                                                <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
        
        
                                            <td>
                                                <label for="fp-date-time">Targets</label>
                                                <input type="number" class="form-control"
                                                    wire:model.prevent="Targets.{{ $index }}.Target" />
                                                @error('Targets.{{ $index }}.Target')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="col-md-12 form-group">
                                                    <label for="fp-date-time">Default Monthly</label>
                                                    <input wire:model.prevent="Targets.{{ $index }}.deadline"
                                                        type="date" id="fp-date-time"
                                                        class="form-control flatpickr-date-time"
                                                        placeholder="YYYY-MM-DD HH:MM" />
                                                    @error('Targets.{{ $index }}.deadline')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-outline-danger" href="#"
                                                    wire:click="removeTargets({{ $index }})">
                                                    <i data-feather="trash-2" class="mr-25"></i>
                                                    <span>Delete</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 m-2">
                                    <button wire:click.prevent="addTargets" type="button" class="btn btn-outline-primary">
                                        <i data-feather="user-plus" class="mr-25"></i>
                                        <span>Add New Row</span>
                                    </button>
                                </div>
                            </div>
                        </div>
        
                        @error('Targets.{{ $index }}.primarykey')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        @if ($countTargets)
                            <div class="m-2">
                                <button wire:click.prevent="submit()" type="submit"
                                    class="btn btn-primary mr-1 data-submit">Submit</button>
                            </div>
                        @else
                            <div class="m-2">
                                <button class="btn btn-outline-primary">DISABLED</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            
                            <script>
                               $(document).ready(function() {
                                  $('#account_type').on('change', function() {
                                     var accountType = $(this).val();
                                     if (accountType) {
                                        $.ajax({
                                           url: '{{ route('get.users') }}',
                                           type: 'GET',
                                           data: { account_type: accountType },
                                           success: function(data) {
                                              $('#user').empty();
                                              $('#user').append('<option value="">Choose Sales Person</option>');
                                              $('#user').append('<option value="ALL">ALL</option>');
                                              data.users.forEach(function(user) {
                                                 $('#user').append('<option value="' + user.user_code + '">' + user.name + '</option>');
                                              });
                                           },
                                           error: function() {
                                              console.log('Error occurred during AJAX request.');
                                           }
                                        });
                                     } else {
                                        $('#user').empty();
                                        $('#user').append('<option value="">Choose Sales Person</option>');
                                     }
                                  });
                               });
                            </script>
