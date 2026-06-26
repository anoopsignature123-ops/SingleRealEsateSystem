@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-house-add me-2"></i>New Booking Payment Details
                </h3>
                <small class="text-muted">New booking payment details report</small>
            </div>
            <span class="badge badge-report">Booking Payment</span>
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
                            <label>Associate ID</label>
                            <select name="associate_id" class="form-select">
                                <option value="">Select Associate</option>
                                @foreach ($associates as $associate)
                                    <option value="{{ $associate->id }}"
                                        {{ request('associate_id') == $associate->id ? 'selected' : '' }}>
                                        {{ $associate->associate_id }}
                                        -
                                        {{ $associate->associate_name }}
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
                            <a href="{{ route('new-booking-payment-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('new-booking-payment-details-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @php
            $totalAmount = $reports->sum('paid_amount');
        @endphp
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>New Booking Payment Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookingPaymentTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Associate ID</th>
                                <th>Associate Name</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Plot No</th>
                                <th>Payment Mode</th>
                                <th>Receipt No</th>
                                <th>Paid Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($reports->count() > 0)
                                @foreach ($reports as $key => $report)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $report->customerBooking?->associate?->associate_id ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->associate?->associate_name ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->booking_code ?? 'N/A' }}</td>
                                        <td>{{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($report->payment_mode ?? 'N/A') }}</td>
                                        <td>{{ $report->receipt_number ?? 'N/A' }}</td>
                                        <td class="text-success fw-bold">
                                            ₹{{ number_format($report->booking_amount ?? 0, 2) }}
                                        </td>
                                        <td>{{ $report->created_at?->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <th colspan="9" class="text-end">Total</th>
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
            $('#bookingPaymentTable').DataTable({
                pageLength: 10,
                scrollX: true,
                columnDefs: [{
                    targets: "_all",
                    defaultContent: "-"
                }]
            });
        });
    </script>
@endpush
