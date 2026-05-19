@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-x-circle me-2"></i>Bounced Cheque Details
                </h3>
                <small class="text-muted">Bounced cheque details report</small>
            </div>
            <span class="badge badge-report">Bounced Cheque</span>
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
                        <div class="col-md-4">
                            <label>Customer Id</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.bounced-cheque-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.bounced-cheque-details-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @php
            $totalAmount = $reports->sum('booking_amount');
        @endphp
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>Bounced Cheque Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bouncedChequeTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Customer Id</th>
                                <th>Customer Name</th>
                                <th>Booking Id</th>
                                <th>Agent Id</th>
                                <th>Plot No</th>
                                <th>Cheque No</th>
                                <th>Cheque Date</th>
                                <th>Bank Name</th>
                                <th>Branch Name</th>
                                <th>Cheque Reason</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>
                                    <td>{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}</td>
                                    <td>{{ $report->customerBooking?->booking_code ?? 'N/A' }}</td>
                                    <td>
                                        {{ $report->customerBooking?->associate?->associate_id ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                                    </td>
                                    <td>{{ $report->cheque_number ?? 'N/A' }}</td>
                                    <td>
                                        {{ $report->cheque_date ? $report->cheque_date->format('d-m-Y') : 'N/A' }}
                                    </td>
                                    <td>{{ $report->bank_name ?? 'N/A' }}</td>
                                    <td> {{ $report->branch_name ?? 'N/A' }}</td>
                                    <td>{{ $report->cheque_reason ?? 'N/A' }}</td>
                                    <td class="text-danger fw-bold">₹{{ number_format($report->booking_amount ?? 0, 2) }}
                                    </td>
                                    <td>{{ $report->created_at?->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot>
                            <tr class="table-dark">
                                <th colspan="11" class="text-end">Total</th>
                                <th>₹{{ number_format($totalAmount, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('#bouncedChequeTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
