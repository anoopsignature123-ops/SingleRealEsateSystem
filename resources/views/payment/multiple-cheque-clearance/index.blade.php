@extends('layouts.app')

@push('title')
    Cheque Clearance
@endpush
@section('content')
    <div class="container-fluid mt-4 cheque-clearance-page">
        <div class="cheque-clearance-hero mb-4">
            <div class="d-flex align-items-center gap-3">
                <span class="cheque-clearance-hero-icon">
                    <i class="bi bi-bank"></i>
                </span>
                <div>
                    <span class="text-success fw-bold text-uppercase small">Payment Verification</span>
                    <h3 class="fw-bold mb-1 text-dark">Cheque Clearance</h3>
                    <p class="text-muted mb-0 small">Select cheque or DD payments and update their clearance status.</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please check:</strong> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="cheque-stat-card">
                    <small>Total Records</small>
                    <strong>{{ $summary['total'] ?? 0 }}</strong>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="cheque-stat-card warning">
                    <small>Pending Clearance</small>
                    <strong>{{ $summary['pending'] ?? 0 }}</strong>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="cheque-stat-card danger">
                    <small>Bounced / Failed</small>
                    <strong>{{ $summary['bounced'] ?? 0 }}</strong>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="cheque-stat-card success">
                    <small>Total Amount</small>
                    <strong>&#8377;{{ number_format((float) ($summary['amount'] ?? 0), 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="cheque-clearance-card">
            <div class="cheque-clearance-toolbar">
                <div>
                    <h5 class="fw-bold mb-1">Cheque / DD Records</h5>
                    <small class="text-muted">Only non-cleared records are shown here.</small>
                </div>

                <div class="cheque-selection-pill d-none" id="selection_summary">
                    <span><strong id="selected_count">0</strong> selected</span>
                    <span class="vr"></span>
                    <span>&#8377;<strong id="selected_amount">0.00</strong></span>
                </div>

                <button type="button" id="bulk_action_btn" class="btn btn-success d-none" data-bs-toggle="modal"
                    data-bs-target="#statusModal">
                    <i class="bi bi-check2-square me-1"></i> Update Selected
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 cheque-clearance-table">
                    <thead>
                        <tr>
                            <th width="48" class="text-center">
                                <input type="checkbox" id="select_all" class="form-check-input">
                            </th>
                            <th>Receipt</th>
                            <th>Customer</th>
                            <th>Project / Plot</th>
                            <th>Booking ID</th>
                            <th>Amount</th>
                            <th>Bank / Ref</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $status = $payment['status'] ?: 'pending';
                                $statusClass = match ($status) {
                                    'cleared' => 'success',
                                    'cancelled' => 'danger',
                                    'bounced' => 'dark',
                                    'mixed' => 'secondary',
                                    default => 'warning',
                                };
                                $amount = (float) ($payment['amount'] ?? 0);
                            @endphp

                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input payment_checkbox"
                                        value="{{ $payment['payment_ids'] }}" data-amount="{{ $amount }}">
                                </td>
                                <td>
                                    <strong>{{ $payment['receipt_number'] ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $payment['created_at']?->format('d-m-Y') ?? '-' }}
                                        @if (($payment['record_count'] ?? 1) > 1)
                                            | {{ $payment['record_count'] }} rows
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $payment['customer_name'] ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payment['customer_code'] ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $payment['projects'] ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Block {{ $payment['blocks'] ?? '-' }} |
                                        Plot {{ $payment['plots'] ?? '-' }}
                                    </small>
                                </td>
                                <td>{{ $payment['booking_codes'] ?? '-' }}</td>
                                <td class="fw-bold text-success">&#8377;{{ number_format($amount, 2) }}</td>
                                <td>
                                    <strong>{{ $payment['bank_name'] ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $payment['reference'] ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border">
                                        {{ strtoupper($payment['payment_mode'] ?? '-') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $payment['cheque_date'] ? $payment['cheque_date']->format('d-m-Y') : '-' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No cheque or DD records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 cheque-status-modal">
                <form method="POST" action="{{ route('multiple-cheque-clearance.store') }}" id="chequeStatusForm">
                    @csrf

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title fw-bold">Update Cheque Status</h5>
                            <small class="text-muted">
                                <span id="modal_selected_count">0</span> selected records will be updated.
                            </small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="payment_ids" id="payment_ids">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Status</label>
                            <select name="cheque_status" id="cheque_status" class="form-select" required>
                                <option value="cleared">Cleared</option>
                                <option value="pending">Pending</option>
                                <option value="bounced">Bounced</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Clearance / Status Date</label>
                            <input type="date" name="cheque_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>

                        <div class="mb-0 d-none" id="reason_box">
                            <label class="form-label fw-semibold">Reason</label>
                            <textarea name="cheque_reason" class="form-control" rows="4" placeholder="Enter reason here..."></textarea>
                            <small class="text-muted">Reason is useful for bounced, cancelled or pending status.</small>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="saveChequeStatusBtn">
                            <span class="btn-label">
                                <i class="bi bi-check2-circle me-1"></i> Save Changes
                            </span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('payment.multiple-cheque-clearance.script')
