@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-calendar-day me-2"></i> Daily Collection Report
                </h3>
                <small class="text-muted">Daily collection summary report</small>
            </div>
            <span class="badge badge-report">Collection Report </span>
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
                            <a href="{{ route('daily-collection-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('daily-collection-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (request()->has('search'))
            @php
                $totalPaidAmount = $reports->sum('paid_amount');
            @endphp
            <div class="card report-card">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>Daily Collection Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dailyCollectionTable" class="table table-hover align-middle nowrap w-100">
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
                                    <th>Paymode / Cheque / DD / Ref No</th>
                                    <th>Paid Amt.</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $key => $report)
                                    @php
                                        $paymentRef = '-';
                                        if ($report->payment_mode == 'Cheque') {
                                            $paymentRef = $report->cheque_number;
                                        } elseif ($report->payment_mode == 'DD') {
                                            $paymentRef = $report->dd_number;
                                        } else {
                                            $paymentRef = $report->transaction_number;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $report->customerBooking?->associate?->associate_id ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }} </td>
                                        <td>{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}</td>
                                        <td>{{ $report->customerBooking?->booking_code ?? 'N/A' }}</td>
                                        <td>{{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($report->plan_type ?? 'N/A') }}</td>
                                        <td>{{ ucfirst($report->payment_mode ?? 'N/A') }}</td>
                                        <td>{{ $report->receipt_number ?? 'N/A' }}</td>
                                        <td class="fw-bold">₹{{ number_format($report->net_payable_amount ?? 0, 2) }}</td>
                                        <td>{{ $paymentRef }}</td>
                                        <td class="text-success fw-bold">
                                            ₹{{ number_format($report->booking_amount ?? 0, 2) }}
                                        </td>
                                        <td>{{ $report->created_at ? $report->created_at->format('d-m-Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- Footer Total --}}
                            <tfoot>
                                <tr>
                                    <th colspan="11" class="text-end">
                                        Total :
                                    </th>
                                    <th class="text-success fw-bold">
                                        ₹{{ number_format($totalPaidAmount, 2) }}
                                    </th>
                                    <th></th>
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
            $('#dailyCollectionTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
