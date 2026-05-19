@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-calendar-x me-2"></i>Daily Dues Report
                </h3>
                <small class="text-muted">
                    Daily dues collection report
                </small>
            </div>
            <span class="badge badge-report">
                Daily Dues
            </span>
        </div>
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>Filter Report
                </h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" name="search" value="1" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.daily-dues-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.daily-dues-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (request()->has('search'))
            <div class="card report-card">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>Daily Dues Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dailyDuesTable" class="table table-hover align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Agent Id</th>
                                    <th>Customer Id</th>
                                    <th>Customer Name</th>
                                    <th>Booking Id</th>
                                    <th>Plot No</th>
                                    <th>Plan Type</th>
                                    <th>Payment Type</th>
                                    <th>Receipt No</th>
                                    <th>Total Cost</th>
                                    <th>Paymode Cheque/DD/ReferenceNo</th>
                                    <th>Paid Amt</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            @php
                                $totalPaid = 0;
                            @endphp
                            <tbody>
                                @foreach ($reports as $key => $report)
                                    @php
                                        $totalPaid += $report->booking_amount ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td> {{ $report->customerBooking?->associate?->associate_id ?? 'N/A' }} </td>
                                        <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->booking_code ?? 'N/A' }}</td>
                                        <td>{{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                        <td>
                                            {{ ucfirst(str_replace('_', ' ', $report->plan_type ?? 'N/A')) }}
                                        </td>
                                        <td>
                                            {{ ucfirst(str_replace('_', ' ', $report->payment_status ?? 'N/A')) }}
                                        </td>
                                        <td>
                                            {{ $report->receipt_number ?? ($report->manual_receipt_number ?? 'N/A') }}
                                        </td>
                                        <td class="fw-bold">
                                            ₹{{ number_format($report->net_payable_amount ?? 0, 2) }}
                                        </td>
                                        <td>
                                            {{ $report->cheque_number ?? ($report->dd_number ?? ($report->transaction_number ?? '
                                            N/A')) }}
                                        </td>
                                        <td class="text-success fw-bold">
                                            ₹{{ number_format($report->booking_amount ?? 0, 2) }}
                                        </td>
                                        <td>
                                            {{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('d-m-Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold table-footer">
                                    <td colspan="11" class="text-end">
                                        Total
                                    </td>
                                    <td class="text-success">
                                        ₹{{ number_format($totalPaid, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('#dailyDuesTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
