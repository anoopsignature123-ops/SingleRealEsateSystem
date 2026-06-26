@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-cash-stack me-2"></i>
                    Payment Collection Dues Summary
                </h3>
                <small class="text-muted">
                    Collection & due amount summary report
                </small>
            </div>
            <span class="badge badge-report">Summary</span>
        </div>
        {{-- Filter --}}
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
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Customer ID</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Select Customer </option>
                                @foreach ($customerIds as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('payment-collection-dues-summary-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('payment-collection-dues-summary-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Collection Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="summaryTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Total Cost</th>
                                <th>Paid Amt.</th>
                                <th>Due Amt.</th>
                                <th>Plot No</th>
                            </tr>
                        </thead>
                        @php
                            $totalCost = 0;
                            $totalPaid = 0;
                            $totalDue = 0;
                        @endphp
                        <tbody>
                            @foreach ($reports as $key => $report)
                                @php
                                    $plotSale = $report->plotSaleDetail;
                                    $payment = $report->payment;
                                    $paidAmount = $report->payments
                                        ->whereIn('payment_status', ['paid', 'cleared'])
                                        ->sum('paid_amount');
                                    $finalAmount = $payment?->net_payable_amount ?? 0;
                                    $dueAmount = $finalAmount - $paidAmount;
                                    $totalCost += $finalAmount;
                                    $totalPaid += $paidAmount;
                                    $totalDue += $dueAmount;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report->customer_code ?? 'N/A' }}</td>
                                    <td class="fw-semibold">{{ $report->primaryDetail?->name ?? 'N/A' }}</td>
                                    <td class="fw-semibold">{{ $report->booking_code ?? 'N/A' }}</td>
                                    <td class="fw-bold">₹{{ number_format($finalAmount, 2) }}</td>
                                    <td class="text-success fw-bold">₹{{ number_format($paidAmount, 2) }}</td>
                                    <td class="text-danger fw-bold">₹{{ number_format($dueAmount, 2) }}</td>
                                    <td>{{ $plotSale?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold table-footer">
                                <td colspan="3"></td>
                                <td>Total</td>
                                <td>₹{{ number_format($totalCost, 2) }}</td>
                                <td class="text-success">₹{{ number_format($totalPaid, 2) }}</td>
                                <td class="text-danger">₹{{ number_format($totalDue, 2) }}</td>
                                <td></td>
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
                scrollX: true
            });
        });
    </script>
@endpush
