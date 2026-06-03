@php
    $payment = $selectedPayment;
    $booking = $payment->customerBooking;
    $plotSale = $payment->plotSaleDetail;
@endphp

<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">Edit Payment Details</h5>

        <form method="POST" action="{{ route('edit-payment-details.update', $payment->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" id="totalPlotCost" value="{{ $plotSale?->total_plot_cost ?? 0 }}">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Customer</label>
                    <input type="text"
                        class="form-control bg-light"
                        readonly
                        value="{{ $booking?->customer_code }} - {{ $booking?->primaryDetail?->name }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Booking ID</label>
                    <input type="text"
                        class="form-control bg-light"
                        readonly
                        value="{{ $plotSale?->booking_code ?? $booking?->booking_code }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Plot</label>
                    <input type="text"
                        class="form-control bg-light"
                        readonly
                        value="{{ $plotSale?->project?->name }} / {{ $plotSale?->block?->block }} / Plot {{ $plotSale?->plotDetail?->plot_number }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Receipt No</label>
                    <input type="text"
                        class="form-control bg-light"
                        readonly
                        value="{{ old('receipt_number', $payment?->receipt_number) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Manual Receipt No</label>
                    <input type="text"
                        name="manual_receipt_number"
                        class="form-control"
                        value="{{ old('manual_receipt_number', $payment?->manual_receipt_number) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Payment Type</label>
                    <select name="plan_type" id="planType" class="form-select">
                        <option value="full_payment"
                            {{ old('plan_type', $payment?->plan_type) == 'full_payment' ? 'selected' : '' }}>
                            Full Payment
                        </option>

                        <option value="emi_plan"
                            {{ old('plan_type', $payment?->plan_type) == 'emi_plan' ? 'selected' : '' }}>
                            EMI Plan
                        </option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Paid Amount</label>
                    <input type="number"
                        name="paid_amount"
                        id="paidAmount"
                        class="form-control"
                        step="0.01"
                        value="{{ old('paid_amount', $payment?->paid_amount ?? $payment?->booking_amount) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Due Amount</label>
                    <input type="text"
                        readonly
                        name="due_amount"
                        id="dueAmount"
                        class="form-control bg-light"
                        value="{{ old('due_amount', $payment?->due_amount) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Pay Mode</label>
                    <select name="payment_mode" id="paymentMode" class="form-select">
                        <option value="cash" {{ old('payment_mode', $payment?->payment_mode) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="cheque" {{ old('payment_mode', $payment?->payment_mode) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="dd" {{ old('payment_mode', $payment?->payment_mode) == 'dd' ? 'selected' : '' }}>DD</option>
                        <option value="neft_rtgs" {{ old('payment_mode', $payment?->payment_mode) == 'neft_rtgs' ? 'selected' : '' }}>NEFT / RTGS</option>
                        <option value="card" {{ old('payment_mode', $payment?->payment_mode) == 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3 emi-field d-none">
                    <label class="form-label fw-semibold">EMI Months</label>
                    <input type="number"
                        name="emi_months"
                        id="emiMonths"
                        class="form-control"
                        value="{{ old('emi_months', $payment?->emi_months) }}">
                </div>

                <div class="col-md-4 mb-3 emi-field d-none">
                    <label class="form-label fw-semibold">EMI Amount</label>
                    <input type="text"
                        readonly
                        id="emiAmount"
                        name="after_booking_payable_amount"
                        class="form-control bg-light"
                        value="{{ old('after_booking_payable_amount', $payment?->after_booking_payable_amount) }}">
                </div>

                <div class="col-md-4 mb-3 bank-field d-none">
                    <label class="form-label fw-semibold">Account Number</label>
                    <input type="text"
                        name="account_number"
                        class="form-control"
                        value="{{ old('account_number', $payment?->account_number) }}">
                </div>

                <div class="col-md-4 mb-3 bank-field d-none">
                    <label class="form-label fw-semibold">Bank Name</label>
                    <input type="text"
                        name="bank_name"
                        class="form-control"
                        value="{{ old('bank_name', $payment?->bank_name) }}">
                </div>

                <div class="col-md-4 mb-3 bank-field d-none">
                    <label class="form-label fw-semibold">Branch Name</label>
                    <input type="text"
                        name="branch_name"
                        class="form-control"
                        value="{{ old('branch_name', $payment?->branch_name) }}">
                </div>

                <div class="col-md-4 mb-3 cheque-field d-none">
                    <label class="form-label fw-semibold">Cheque Number</label>
                    <input type="text"
                        name="cheque_number"
                        class="form-control"
                        value="{{ old('cheque_number', $payment?->cheque_number) }}">
                </div>

                <div class="col-md-4 mb-3 cheque-field d-none">
                    <label class="form-label fw-semibold">Cheque Date</label>
                    <input type="date"
                        name="cheque_date"
                        class="form-control"
                        value="{{ old('cheque_date', $payment?->cheque_date) }}">
                </div>

                <div class="col-md-4 mb-3 dd-field d-none">
                    <label class="form-label fw-semibold">DD Number</label>
                    <input type="text"
                        name="dd_number"
                        class="form-control"
                        value="{{ old('dd_number', $payment?->dd_number) }}">
                </div>

                <div class="col-md-4 mb-3 transaction-field d-none">
                    <label class="form-label fw-semibold">Transaction Number</label>
                    <input type="text"
                        name="transaction_number"
                        class="form-control"
                        value="{{ old('transaction_number', $payment?->transaction_number) }}">
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-success px-4">
                        <i class="fa fa-save me-1"></i>
                        Update Payment
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>