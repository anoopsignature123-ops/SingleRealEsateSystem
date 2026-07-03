@extends('layouts.app')

@push('title')
    Cheque Details Report
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">

        <div class="transaction-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-bank text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Cheque Details Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">Cheque Details Report</h3>
                        <p class="text-muted small mb-0">
                            Cleared and bounced cheque payment details.
                        </p>
                    </div>
                </div>

                <a href="{{ route('cheque-details-report.export', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Cheques</small>
                        <h4 class="fw-bold mb-0">{{ $summary['total_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Cleared</small>
                        <h4 class="fw-bold text-success mb-0">{{ $summary['cleared_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Bounced</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['bounced_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Amount</small>
                        <h4 class="fw-bold text-primary mb-0">
                            ₹{{ number_format($summary['total_amount'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;">
                        <i class="bi bi-funnel"></i>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">Filter Report</h5>
                        <small class="text-muted">
                            Filter by payment type and cheque status.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Payment Type</label>
                            <select name="criteria" class="form-select">
                                <option value="">All Payment Types</option>
                                <option value="full_payment" {{ request('criteria') == 'full_payment' ? 'selected' : '' }}>
                                    Full Payment
                                </option>
                                <option value="emi_plan" {{ request('criteria') == 'emi_plan' ? 'selected' : '' }}>
                                    Installment Amount
                                </option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Cheque Status</label>
                            <select name="cheque_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="cleared" {{ request('cheque_status') == 'cleared' ? 'selected' : '' }}>
                                    Cleared
                                </option>
                                <option value="bounced" {{ request('cheque_status') == 'bounced' ? 'selected' : '' }}>
                                    Bounced
                                </option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('cheque-details-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-table text-success me-2"></i>
                            Cheque Details
                        </h5>
                        <small class="text-muted">
                            Cleared and bounced cheque transaction records.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $reports->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="chequeTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Payment Type</th>
                                <th>Pay Mode</th>
                                <th class="text-end">Amount</th>
                                <th>Bank Account No.</th>
                                <th>Cheque No.</th>
                                <th>Bank Name</th>
                                <th>Bank Branch</th>
                                <th>Pay Date</th>
                                <th>Cheque Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $key => $report)
                                @php
                                    $status = strtolower($report->cheque_status ?? 'N/A');

                                    $statusClass = match ($status) {
                                        'cleared' => 'bg-success',
                                        'bounced' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>

                                    <td class="fw-semibold">
                                        {{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $report->customerBooking?->booking_code ?? 'N/A' }}
                                    </td>

                                    <td>{{ ucfirst(str_replace('_', ' ', $report->plan_type ?? 'N/A')) }}</td>

                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-2">
                                            {{ strtoupper($report->payment_mode ?? 'N/A') }}
                                        </span>
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($report->paid_amount ?? $report->booking_amount ?? 0, 2) }}
                                    </td>

                                    <td>{{ $report->account_number ?? 'N/A' }}</td>

                                    <td>{{ $report->cheque_number ?? 'N/A' }}</td>

                                    <td>{{ $report->bank_name ?? 'N/A' }}</td>

                                    <td>{{ $report->branch_name ?? 'N/A' }}</td>

                                    <td>
                                        {{ $report->cheque_date ? date('d-m-Y', strtotime($report->cheque_date)) : 'N/A' }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                            {{ strtoupper($report->cheque_status ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No cheque records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="6" class="text-end">Total</td>
                                <td class="text-end text-primary">
                                    ₹{{ number_format($summary['total_amount'], 2) }}
                                </td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#chequeTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No cheque records found.'
                }
            });
        });
    </script>
@endpush