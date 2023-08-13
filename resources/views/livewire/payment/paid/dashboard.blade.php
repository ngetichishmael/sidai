<div>
    <div class="card">
        <h5 class="card-header"></h5>
        <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
            <div class="col-md-3 user_role">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="search"></i></span>
                    </div>
                    <input wire:model="search" type="text" id="fname-icon" class="form-control" name="fname-icon"
                        placeholder="Search" />
                </div>
            </div>
            <div class="col-md-1 user_role">
                <div class="form-group">
                    <label for="selectSmall">Per Page</label>
                    <select wire:model="perPage" class="form-control form-control-sm" id="selectSmall">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="validationTooltip01">Start Date</label>
                    <input wire:model="fromDate" name="fromDate" type="date" class="form-control"
                        id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="validationTooltip01">End Date</label>
                    <input wire:model="toDate" name="startDate" type="date" class="form-control"
                        id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                </div>
            </div>

        </div>
    </div>
    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $paymentMethod == 'PaymentMethods.Mpesa' ? 'active' : '' }}"
                    wire:click="$set('paymentMethod', 'PaymentMethods.Mpesa')">Mpesa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $paymentMethod == 'PaymentMethods.Cheque' ? 'active' : '' }}"
                    wire:click="$set('paymentMethod', 'PaymentMethods.Cheque')">Cheque</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $paymentMethod == 'PaymentMethods.Cash' ? 'active' : '' }}"
                    wire:click="$set('paymentMethod', 'PaymentMethods.Cash')">Cash</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $paymentMethod == 'PaymentMethods.BankTransfer' ? 'active' : '' }}"
                    wire:click="$set('paymentMethod', 'PaymentMethods.BankTransfer')">Bank to Bank</a>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Sales Agent</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $count=>$payment)
                        <tr>
                            <td>{!! $count + 1 !!}</td>
                            <td>{!! $payment->order_id !!}</td>
                            <td>{!! $payment->reference_number ?? '' !!}</td>
                            <td>{!! $payment->payment_date !!}</td>
                            <td>{!! $payment->customer_name ?? '' !!}</td>
                            <td>{!! $payment->name !!}</td>
                            <td>{!! number_format($payment->amount, 2) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
