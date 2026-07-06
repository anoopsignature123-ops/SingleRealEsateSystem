@extends('layouts.app')

@push('title')
    Dues Installment Report
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
                        <i class="bi bi-calendar2-check text-success"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Dues Installment Report</span>
                        <h3 class="fw-bold text-dark mb-1">Dues Installment Report</h3>
                        <p class="text-muted small mb-0">EMI due installment summary report.</p>
                    </div>
                </div>

                <a href="{{ route('dues-installment-report.export', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export
                </a>
            </div>
        </div>

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
                <div class="card border border-warning-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Due Installments</small>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['total_due_installments'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Paid Amount</small>
                        <h4 class="fw-bold text-success mb-0">₹{{ number_format($summary['paid_amount'], 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Balance Amount</small>
                        <h4 class="fw-bold text-danger mb-0">₹{{ number_format($summary['balance_amount'], 2) }}</h4>
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
                        <small class="text-muted">Filter EMI dues by date or customer.</small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                                <i class="bi bi-search me-1"></i> Search
                            </button>

                            <a href="{{ route('dues-installment-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
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
                            Due Installment Summary
                        </h5>
                        <small class="text-muted">Grouped by booking code for plot bookings.</small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $reports->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="dueInstallmentTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Agent ID</th>
                                <th>Customer</th>
                                <th>Booking ID</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Total Plot</th>
                                <th>Booking Date</th>
                                <th class="text-end">Installment Amt</th>
                                <th class="text-end">Total Ins Amt</th>
                                <th class="text-end">Paid Ins Amt</th>
                                <th class="text-end">Balance Amt</th>
                                <th>No Of Due Ins</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report['agent_code'] }}</td>

                                    <td>
                                        <div class="fw-bold">{{ $report['customer_code'] }}</div>
                                        <small class="text-muted">{{ $report['customer_name'] }}</small>
                                    </td>

                                    <td class="fw-semibold">{{ $report['booking_code'] }}</td>
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

                                    <td>{{ $report['booking_date'] }}</td>

                                    <td class="text-end fw-bold">
                                        ₹{{ number_format($report['installment_amount'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        ₹{{ number_format($report['total_amount'], 2) }}
                                    </td>

                                    <td class="text-end text-success fw-bold">
                                        ₹{{ number_format($report['paid_amount'], 2) }}
                                    </td>

                                    <td class="text-end text-danger fw-bold">
                                        ₹{{ number_format($report['balance_amount'], 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border rounded-pill px-3 py-2">
                                            {{ $report['due_installment'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No due installment records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="9" class="text-end">Total</td>
                                <td></td>
                                <td class="text-end">₹{{ number_format($summary['total_amount'], 2) }}</td>
                                <td class="text-end text-success">₹{{ number_format($summary['paid_amount'], 2) }}</td>
                                <td class="text-end text-danger">₹{{ number_format($summary['balance_amount'], 2) }}</td>
                                <td>{{ $summary['total_due_installments'] }}</td>
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
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No due installment records found.'
                }
            });
        });
    </script>
@endpush
