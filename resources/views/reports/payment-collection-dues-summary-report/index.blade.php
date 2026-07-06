@extends('layouts.app')

@push('title')
    Payment Collection Dues Summary
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="transaction-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-cash-stack text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Payment Collection Dues Summary
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            Payment Collection Dues Summary
                        </h3>
                        <p class="text-muted small mb-0">
                            Collection and due amount summary report.
                        </p>
                    </div>
                </div>

                <a href="{{ route('payment-collection-dues-summary-report.export', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        {{-- Summary --}}
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
                        <small class="text-muted fw-semibold">Total Plots</small>
                        <h4 class="fw-bold text-primary mb-0">{{ $summary['total_plots'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Collection</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_paid'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Due</small>
                        <h4 class="fw-bold text-danger mb-0">
                            ₹{{ number_format($summary['total_due'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
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
                            Filter summary by booking date or customer.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ request('date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer ID</label>
                            <select name="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customerIds as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }}
                                        @if ($customer->primaryDetail?->name)
                                            - {{ $customer->primaryDetail->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('payment-collection-dues-summary-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DataTable --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-table text-success me-2"></i>
                            Collection Summary
                        </h5>
                        <small class="text-muted">
                            Plot bookings are grouped by booking code.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $reports->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="summaryTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Total Plot</th>
                                <th class="text-end">Total Cost</th>
                                <th class="text-end">Paid Amt.</th>
                                <th class="text-end">Due Amt.</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="fw-bold">{{ $report['customer_code'] }}</span>
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $report['customer_name'] }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $report['booking_code'] }}
                                    </td>

                                    <td>{{ $report['project'] }}</td>

                                    <td>{{ $report['block'] }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            {{ $report['plots'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-2">
                                            {{ $report['plot_count'] }} Plot(s)
                                        </span>
                                    </td>

                                    <td class="text-end fw-bold">
                                        ₹{{ number_format($report['total_cost'], 2) }}
                                    </td>

                                    <td class="text-end text-success fw-bold">
                                        ₹{{ number_format($report['paid_amount'], 2) }}
                                    </td>

                                    <td class="text-end text-danger fw-bold">
                                        ₹{{ number_format($report['due_amount'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No collection summary records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="8" class="text-end">Total</td>
                                <td class="text-end">
                                    ₹{{ number_format($summary['total_cost'], 2) }}
                                </td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_paid'], 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    ₹{{ number_format($summary['total_due'], 2) }}
                                </td>
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
            $('#summaryTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No collection summary records found.'
                }
            });
        });
    </script>
@endpush
