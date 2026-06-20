@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4 emi-payment-page">
        <div class="emi-payment-hero mb-4">
            <div class="d-flex align-items-center gap-3">
                <span class="emi-payment-hero-icon">
                    <i class="bi bi-calendar2-week"></i>
                </span>
                <div>
                    <span class="text-success fw-bold text-uppercase small">EMI Collection</span>
                    <h3 class="fw-bold mb-1 text-dark">EMI Payment</h3>
                    <p class="text-muted mb-0 small">Select project, block and plot to collect pending EMI.</p>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please check:</strong> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('emi-payment.store') }}" id="emiPaymentForm">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm emi-payment-card">
                        <div class="card-body p-4">
                            <input type="hidden" name="customer_booking_id" id="customer_booking_id">
                            <input type="hidden" name="plot_sale_detail_id" id="plot_sale_detail_id">
                            <input type="hidden" id="monthly_emi_value">
                            <input type="hidden" id="max_due_amount" value="0">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Project <span class="text-danger">*</span></label>
                                    <select id="project_id" class="form-select">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Block <span class="text-danger">*</span></label>
                                    <select id="block_id" class="form-select">
                                        <option value="">Select Block</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Plot <span class="text-danger">*</span></label>
                                    <select id="plot_id" class="form-select">
                                        <option value="">Select Plot</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Booking ID</label>
                                    <input id="booking_id" class="form-control bg-light" readonly
                                        placeholder="Auto filled after plot selection">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Customer ID</label>
                                    <input id="customer_id" class="form-control bg-light" readonly
                                        placeholder="Auto filled after plot selection">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Customer Name</label>
                                    <input id="customer_name" class="form-control bg-light" readonly
                                        placeholder="Auto filled after plot selection">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">EMI Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" inputmode="decimal" name="booking_amount" id="booking_amount_input"
                                        value="{{ old('booking_amount') }}"
                                        class="form-control @error('booking_amount') is-invalid @enderror"
                                        placeholder="Enter EMI amount">
                                    @error('booking_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Minimum EMI:
                                        <span id="minimum_emi" class="text-success fw-bold">&#8377;0.00</span>
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Payment Mode <span class="text-danger">*</span></label>
                                    <select name="payment_mode" id="payment_mode" class="form-select">
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="dd">DD</option>
                                        <option value="neft_rtgs">NEFT / RTGS</option>
                                        <option value="card">Card</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 bank-field d-none">
                                    <label class="form-label fw-semibold">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                                </div>

                                <div class="col-md-6 mb-3 bank-field d-none">
                                    <label class="form-label fw-semibold">Account Number</label>
                                    <input type="text" name="account_number" class="form-control" placeholder="Enter account number">
                                </div>

                                <div class="col-md-6 mb-3 bank-field d-none">
                                    <label class="form-label fw-semibold">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control" placeholder="Enter branch name">
                                </div>

                                <div class="col-md-6 mb-3 cheque-field d-none">
                                    <label class="form-label fw-semibold">Cheque Number</label>
                                    <input type="text" name="cheque_number" class="form-control" placeholder="Enter cheque number">
                                </div>

                                <div class="col-md-6 mb-3 cheque-field d-none">
                                    <label class="form-label fw-semibold">Cheque Date</label>
                                    <input type="date" name="cheque_date" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3 dd-field d-none">
                                    <label class="form-label fw-semibold">DD Number</label>
                                    <input type="text" name="dd_number" class="form-control" placeholder="Enter DD number">
                                </div>

                                <div class="col-md-6 mb-3 transaction-field d-none">
                                    <label class="form-label fw-semibold">Transaction Number</label>
                                    <input type="text" name="transaction_number" class="form-control"
                                        placeholder="Enter transaction number">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Remark</label>
                                    <textarea name="remark" rows="3" class="form-control" placeholder="Enter remark">{{ old('remark') }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success px-4" id="submitEmiPaymentBtn">
                                        <span class="btn-label">
                                            <i class="bi bi-check2-circle me-1"></i> Submit EMI Payment
                                        </span>
                                        <span class="btn-loader d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"
                                                aria-hidden="true"></span>
                                            Processing...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('payment.emi-payment.summary')
            </div>
        </form>
    </div>
@endsection

@include('payment.emi-payment.script')
