@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-calendar2-check me-2"></i>Dues Installment Report
                </h3>
                <small class="text-muted">EMI due installment summary report</small>
            </div>
            <span class="badge badge-report">Dues Report</span>
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
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Customer ID</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Select Customer</option>
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
                            <a href="{{ route('admin.dues-installment-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                            <a href="{{ route('admin.dues-installment-report.export', request()->all()) }}"
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
                    Due Installment Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dueInstallmentTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Agent ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Booking Date</th>
                                <th>Installment Amt</th>
                                <th>Total Ins Amt</th>
                                <th>Paid Ins Amt</th>
                                <th>Balance Amt</th>
                                <th>No Of Due Ins</th>
                            </tr>
                        </thead>
                        @php
                            $grandInstallment = 0;
                            $grandTotal = 0;
                            $grandPaid = 0;
                            $grandBalance = 0;
                        @endphp
                        <tbody>
                            @foreach ($reports as $key => $report)
                                @php
                                    $totalAmount = $report->net_payable_amount ?? 0;
                                    $paidAmount = $report->customerBooking?->payments?->sum('booking_amount') ?? 0;
                                    $balance = $totalAmount - $paidAmount;
                                    $emiMonths = $report->emi_months ?? 1;
                                    $installment = 0;
                                    if ($emiMonths > 0) {
                                        $installment = $totalAmount / $emiMonths;
                                    }
                                    $dueInstallment = 0;
                                    if ($installment > 0) {
                                        $dueInstallment = floor($balance / $installment);
                                    }
                                    $grandInstallment += $installment;
                                    $grandTotal += $totalAmount;
                                    $grandPaid += $paidAmount;
                                    $grandBalance += $balance;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report->customerBooking?->associate?->associate_code ?? 'N/A' }}</td>
                                    <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>
                                    <td class="fw-semibold">{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                    </td>
                                    <td class="fw-semibold">{{ $report->customerBooking?->booking_code ?? 'N/A' }}</td>
                                    <td>{{ $report->customerBooking?->created_at?->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td class="fw-bold">₹{{ number_format($installment, 2) }}</td>
                                    <td class="fw-bold">₹{{ number_format($totalAmount, 2) }}</td>
                                    <td class="text-success fw-bold">₹{{ number_format($paidAmount, 2) }}</td>
                                    <td class="text-danger fw-bold">₹{{ number_format($balance, 2) }}</td>
                                    <td class="fw-bold">{{ $dueInstallment }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="6" class="text-end">Total</td>
                                <td>₹{{ number_format($grandInstallment, 2) }}</td>
                                <td>₹{{ number_format($grandTotal, 2) }}</td>
                                <td class="text-success">
                                    ₹{{ number_format($grandPaid, 2) }}
                                </td>
                                <td class="text-danger">
                                    ₹{{ number_format($grandBalance, 2) }}

                                </td>
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
            $('#dueInstallmentTable').DataTable({
                pageLength: 10,
                scrollX: true,
                ordering: false
            });
        });
    </script>
@endpush
