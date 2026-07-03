@extends('layouts.app')

@push('title')
    Daily Collection & Due Collection Report
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
                        <i class="bi bi-calendar-day text-success"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Daily Collection & Due Collection Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">Daily Collection & Due Collection Report</h3>
                        <p class="text-muted small mb-0">
                            View daily received collection and pending due collection details.
                        </p>
                    </div>
                </div>

                <a href="{{ route('daily-collection-report.export', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export
                </a>
            </div>
        </div>

        @if (request()->has('search'))
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Total Records</small>
                            <h4 class="fw-bold mb-0">{{ $summary['total_records'] }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-primary-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Total Cost</small>
                            <h4 class="fw-bold text-primary mb-0">
                                ₹{{ number_format($summary['total_cost'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Daily Collection</small>
                            <h4 class="fw-bold text-success mb-0">
                                ₹{{ number_format($summary['total_paid'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Due Collection</small>
                            <h4 class="fw-bold text-danger mb-0">
                                ₹{{ number_format($summary['total_due'], 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                            Filter collection and due collection by date range.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control"
                                value="{{ request('from_date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control"
                                value="{{ request('to_date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Report Type</label>
                            <select name="report_type" class="form-select">
                                <option value="">All</option>
                                <option value="collection" {{ request('report_type') == 'collection' ? 'selected' : '' }}>
                                    Daily Collection
                                </option>
                                <option value="due" {{ request('report_type') == 'due' ? 'selected' : '' }}>
                                    Due Collection
                                </option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" name="search" value="1" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i> Search
                            </button>

                            <a href="{{ route('daily-collection-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (request()->has('search'))
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-table text-success me-2"></i>
                                Collection & Due Collection Data
                            </h5>
                            <small class="text-muted">
                                Paid amount shows daily collection and due amount shows pending collection.
                            </small>
                        </div>

                        <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                            {{ $reports->count() }} Records
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table id="dailyCollectionTable" class="table table-hover align-middle nowrap w-100">
                            <thead class="table-success">
                                <tr>
                                    <th>SNo.</th>
                                    <th>Agent ID</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Booking ID</th>
                                    <th>Plot No</th>
                                    <th>Plan Type</th>
                                    <th>Payment Type</th>
                                    <th>Receipt No</th>
                                    <th class="text-end">Total Cost</th>
                                    <th class="text-end">Daily Collection</th>
                                    <th class="text-end">Due Collection</th>
                                    <th>Paymode / Cheque / DD / Ref No</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($reports as $key => $report)
                                    @php
                                        $mode = strtolower($report->payment_mode ?? '');
                                        $paymentRef = '-';

                                        if ($mode === 'cheque') {
                                            $paymentRef = $report->cheque_number ?? '-';
                                        } elseif ($mode === 'dd') {
                                            $paymentRef = $report->dd_number ?? '-';
                                        } else {
                                            $paymentRef = $report->transaction_number ?? '-';
                                        }

                                        $totalCost = (float) ($report->net_payable_amount ?? 0);
                                        $paidAmount = (float) ($report->paid_amount ?? $report->booking_amount ?? 0);
                                        $dueAmount = max(0, $totalCost - $paidAmount);
                                    @endphp

                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            {{ $report->customerBooking?->associate?->associate_code ?? $report->customerBooking?->associate?->associate_id ?? 'N/A' }}
                                        </td>

                                        <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>

                                        <td class="fw-semibold">
                                            {{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                        </td>

                                        <td class="fw-semibold">
                                            {{ $report->customerBooking?->booking_code ?? 'N/A' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark border rounded-pill">
                                                {{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td>{{ ucfirst(str_replace('_', ' ', $report->plan_type ?? 'N/A')) }}</td>

                                        <td>{{ ucfirst(str_replace('_', ' ', $report->transaction_category ?? 'N/A')) }}</td>

                                        <td>{{ $report->receipt_number ?? 'N/A' }}</td>

                                        <td class="text-end fw-bold text-primary">
                                            ₹{{ number_format($totalCost, 2) }}
                                        </td>

                                        <td class="text-end text-success fw-bold">
                                            ₹{{ number_format($paidAmount, 2) }}
                                        </td>

                                        <td class="text-end text-danger fw-bold">
                                            ₹{{ number_format($dueAmount, 2) }}
                                        </td>

                                        <td>{{ $paymentRef }}</td>

                                        <td>{{ $report->created_at ? $report->created_at->format('d-m-Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-5">
                                            <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                            <span class="text-muted">No collection records found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="9" class="text-end">Total</td>
                                    <td class="text-end text-primary">
                                        ₹{{ number_format($summary['total_cost'], 2) }}
                                    </td>
                                    <td class="text-end text-success">
                                        ₹{{ number_format($summary['total_paid'], 2) }}
                                    </td>
                                    <td class="text-end text-danger">
                                        ₹{{ number_format($summary['total_due'], 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-search fs-1 text-muted d-block mb-2"></i>
                    <h5 class="fw-bold mb-1">Search Collection Report</h5>
                    <p class="text-muted mb-0">
                        Select date range and click search to view daily collection and due collection report.
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            if ($('#dailyCollectionTable').length) {
                $('#dailyCollectionTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    responsive: false,
                    scrollX: true,
                    language: {
                        emptyTable: 'No collection records found.'
                    }
                });
            }
        });
    </script>
@endpush