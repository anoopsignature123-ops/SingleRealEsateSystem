@extends('layouts.app')

@section('content')
    @php
        $totalMonthlyEmi = $payments->sum(fn ($payment) => (float) ($payment->group_monthly_emi ?? 0));
        $totalDue = $payments->sum(fn ($payment) => (float) ($payment->group_due_amount ?? 0));
        $multiplePlotCount = $payments->filter(fn ($payment) => (int) ($payment->group_plot_count ?? 0) > 1)->count();
    @endphp

    <div class="container-fluid mt-4 transaction-page update-emi-date-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">EMI Schedule</span>
                        <h3 class="fw-bold mb-1 text-dark">Update EMI Date</h3>
                        <p class="text-muted mb-0 small">Select EMI booking records and update their next EMI date.</p>
                    </div>
                </div>

                <button type="button" id="bulk_update_btn" class="btn btn-success d-none" data-bs-toggle="modal"
                    data-bs-target="#bulkDateModal">
                    <i class="bi bi-calendar-check me-1"></i>
                    Update Selected
                    <span class="selected-count ms-1">(0)</span>
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                <strong>Please check:</strong> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-list-check"></i></span>
                    <div>
                        <small>Total Records</small>
                        <strong>{{ $payments->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-grid-3x3-gap"></i></span>
                    <div>
                        <small>Multiple Plot Groups</small>
                        <strong>{{ $multiplePlotCount }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-cash-coin"></i></span>
                    <div>
                        <small>Monthly EMI</small>
                        <strong>&#8377;{{ number_format($totalMonthlyEmi, 2) }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Total Due</small>
                        <strong>&#8377;{{ number_format($totalDue, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="transaction-card transaction-history-card mb-4">
            <div class="transaction-history-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-section-title-icon">
                        <i class="bi bi-calendar-range"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-1">EMI Date Records</h5>
                        <small class="text-muted">Grouped multiple plot bookings are shown as one record.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $payments->count() }} Records</span>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 px-3 py-3 border-bottom bg-light">
                <label class="form-check d-flex align-items-center gap-2 mb-0 fw-semibold text-muted">
                    <input type="checkbox" id="select_all" class="form-check-input m-0">
                    <span>Select all visible records</span>
                </label>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <strong id="selected_count">0</strong> selected
                    </span>
                </div>
            </div>

            <div class="transaction-table-wrap">
                <div class="table-responsive">
                    <table class="table transaction-table align-middle mb-0" id="emiDateTable">
                        <thead>
                            <tr>
                                <th width="52" class="text-center">Pick</th>
                                <th>Customer</th>
                                <th>Booking / Plots</th>
                                <th>Monthly EMI</th>
                                <th>Remaining Due</th>
                                <th>Months</th>
                                <th>Last EMI</th>
                                <th>Current EMI Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($payments as $payment)
                                @php
                                    $booking = $payment->customerBooking;
                                    $plotSale = $payment->plotSaleDetail;
                                    $plotCount = (int) ($payment->group_plot_count ?? 1);
                                    $plotNumbers = $payment->group_plot_numbers ?: ($plotSale?->plotDetail?->plot_number ?? '-');
                                    $projects = $payment->group_projects ?: ($plotSale?->project?->name ?? '-');
                                    $blocks = $payment->group_blocks ?: ($plotSale?->block?->block ?? '-');
                                    $groupEmiDate = $payment->group_emi_date ?? null;
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input payment_checkbox"
                                            value="{{ $payment->group_payment_ids ?? $payment->id }}">
                                    </td>

                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $booking?->primaryDetail?->name ?? ($booking?->customer_name ?? '-') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $booking?->customer_code ?? '-' }}
                                            @if ($booking?->associate_code)
                                                | Agent {{ $booking->associate_code }}
                                            @endif
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-bold text-success">
                                            {{ $plotSale?->booking_code ?? ($booking?->booking_code ?? '-') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $projects }} / Block {{ $blocks }} / Plot {{ $plotNumbers }}
                                        </small>
                                        @if ($plotCount > 1)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                                {{ $plotCount }} Plots
                                            </span>
                                        @endif
                                    </td>

                                    <td class="fw-bold text-success">
                                        &#8377;{{ number_format((float) ($payment->group_monthly_emi ?? 0), 2) }}
                                    </td>

                                    <td class="fw-bold text-danger">
                                        &#8377;{{ number_format((float) ($payment->group_due_amount ?? 0), 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $payment->group_emi_months ?? (($payment->emi_months ?? 0).' Months') }}
                                        </span>
                                    </td>

                                    <td>{{ $payment->created_at ? $payment->created_at->format('d-M-Y') : '-' }}</td>

                                    <td>
                                        @if ($groupEmiDate)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                {{ \Carbon\Carbon::parse($groupEmiDate)->format('d-M-Y') }}
                                            </span>
                                        @elseif ($payment->group_has_mixed_emi_dates ?? false)
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                                Mixed Dates
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                        No EMI records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkDateModal" tabindex="-1" aria-labelledby="bulkDateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <form method="POST" action="{{ route('update-emi-date.store') }}" id="updateEmiDateForm">
                    @csrf

                    <div class="modal-header bg-light border-bottom">
                        <div>
                            <span class="text-success fw-bold text-uppercase small">Bulk Update</span>
                            <h5 class="modal-title fw-bold mb-0" id="bulkDateModalLabel">Update EMI Date</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="payment_ids" id="payment_ids">

                        <div class="alert alert-success border-success-subtle bg-success-subtle text-success d-flex gap-2 mb-3">
                            <i class="bi bi-info-circle"></i>
                            <span>
                                EMI date will be updated for
                                <strong id="modal_selected_count">0</strong>
                                selected record(s).
                            </span>
                        </div>

                        <label class="form-label fw-semibold">Select New EMI Date</label>
                        <input type="date" name="emi_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4" id="updateEmiDateBtn">
                            <span class="btn-label">
                                <i class="bi bi-save me-1"></i> Save Changes
                            </span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('payment.update-emi-date.script')
