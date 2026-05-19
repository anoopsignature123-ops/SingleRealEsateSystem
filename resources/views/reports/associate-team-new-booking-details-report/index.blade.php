@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-people-fill me-2"></i> Associate Team New Booking Details
                </h3>
                <small class="text-muted">Associate team new booking details report</small>
            </div>
            <span class="badge badge-report">Team Booking</span>
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
                            <button type="submit" name="search" value="1" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.associate-team-new-booking-details-report.index') }}"
                                class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.associate-team-new-booking-details-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (request()->has('search'))
            @php
                $totalPaidAmount = $reports->sum(function ($report) {
                    return $report->payment?->booking_amount ?? 0;
                });
            @endphp
            <div class="card report-card">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>Associate Team New Booking Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="associateTeamBookingTable" class="table table-hover align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Agent Id</th>
                                    <th>Position</th>
                                    <th>Customer Id</th>
                                    <th>Customer Name</th>
                                    <th>Booking Id</th>
                                    <th>Plot No</th>
                                    <th>Plan Type</th>
                                    <th>Payment Type</th>
                                    <th>Total Cost</th>
                                    <th>Paymode</th>
                                    <th>Paid Amt.</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $key => $report)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $report->associate?->associate_id ?? 'N/A' }}</td>
                                        <td>{{ $report->associate?->rank?->designation ?? 'N/A' }}</td>
                                        <td> {{ $report->customer_code ?? 'N/A' }}</td>
                                        <td>{{ $report->primaryDetail?->name ?? 'N/A' }}</td>
                                        <td>{{ $report->booking_code ?? 'N/A' }}</td>
                                        <td>
                                            {{ $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                                        </td>
                                        <td>{{ ucfirst($report->payment?->plan_type ?? 'N/A') }}</td>
                                        <td>
                                            {{ ucfirst($report->payment?->payment_status ?? 'N/A') }}
                                        </td>
                                        <td class="fw-bold text-primary">
                                            ₹{{ number_format($report->plotSaleDetail?->total_plot_cost ?? 0, 2) }}
                                        </td>
                                        <td>{{ ucfirst($report->payment?->payment_mode ?? 'N/A') }}</td>
                                        <td class="fw-bold text-success">
                                            ₹{{ number_format($report->payment?->booking_amount ?? 0, 2) }}
                                        </td>
                                        <td>{{ $report->created_at?->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr class="table-dark">
                                    <th colspan="11" class="text-end">Total</th>
                                    <th>₹{{ number_format($totalPaidAmount, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot> --}}
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
            $('#associateTeamBookingTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
