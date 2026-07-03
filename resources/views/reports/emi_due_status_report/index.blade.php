@extends('layouts.app')

@push('title')
    EMI Due Status Report
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
                        <i class="bi bi-calendar2-week fs-3"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            EMI Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            EMI Due Status Report
                        </h3>
                        <p class="text-muted small mb-0">
                            Track due, overdue, hold and completed EMI status as of
                            {{ $asOfDate->format('d M Y') }}.
                        </p>
                    </div>
                </div>

                <a href="{{ route('emi-due-status-report.export', request()->all()) }}"
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
                        <small class="text-muted fw-semibold">Total EMI Groups</small>
                        <h4 class="fw-bold mb-0">{{ $summary['total_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Pending EMI</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $summary['pending_installments'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-warning-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Hold Groups</small>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['hold_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Due Amount</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_due_amount'], 2) }}
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
                            Filter by customer, mobile, due date or current EMI status.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-2 col-md-4">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ request('customer_name') }}"
                                class="form-control" placeholder="Enter customer name">
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ request('mobile') }}"
                                class="form-control" placeholder="Enter mobile number">
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <label class="form-label fw-semibold">As Of Date</label>
                            <input type="date" name="as_of_date"
                                value="{{ request('as_of_date', $asOfDate->format('Y-m-d')) }}"
                                class="form-control">
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <label class="form-label fw-semibold">Next Due Date</label>
                            <input type="date" name="due_date" value="{{ request('due_date') }}"
                                class="form-control">
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach ([
                                    'due' => 'Due',
                                    'overdue' => 'Overdue',
                                    'hold' => 'Hold',
                                    'upcoming' => 'Upcoming',
                                    'completed' => 'Completed',
                                ] as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('emi-due-status-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DataTable --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-table text-success me-2"></i>
                            Due EMI Records
                        </h5>
                        <small class="text-muted">
                            Grouped by booking code. Click Total Plot to view plot details.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $reports->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="emiStatusTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Booking / Customer</th>
                                <th>Project</th>
                                <th>Total Plot</th>
                                <th class="text-end">Monthly EMI</th>
                                <th class="text-end">Due Amount</th>
                                <th>EMI Progress</th>
                                <th>Next Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $index => $report)
                                @php
                                    $badgeClass = match ($report['status']) {
                                        'completed' => 'bg-success',
                                        'overdue' => 'bg-danger',
                                        'due' => 'bg-warning text-dark',
                                        'hold' => 'bg-info text-dark',
                                        'upcoming' => 'bg-primary',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                            {{ $report['agent_id'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-bold">{{ $report['booking_id'] }}</div>
                                        <div class="fw-semibold">{{ $report['customer_name'] }}</div>
                                        <small class="text-muted">
                                            {{ $report['customer_id'] }} | {{ $report['mobile'] }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $report['project'] }}</div>
                                        <small class="text-muted">Block {{ $report['block'] }}</small>
                                    </td>

                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#emiPlotModal{{ $index }}">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ $report['plot_count'] }} Plot(s)
                                        </button>
                                    </td>

                                    <td class="text-end fw-bold">
                                        ₹{{ number_format($report['monthly_emi'], 2) }}
                                    </td>

                                    <td class="text-end">
                                        <div class="fw-bold text-danger">
                                            ₹{{ number_format($report['total_due_amount'], 2) }}
                                        </div>
                                        <small class="text-muted">
                                            Hold: ₹{{ number_format($report['hold_amount'], 2) }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                            <span class="badge bg-success-subtle text-success border">
                                                {{ $report['paid_installments'] }} Paid
                                            </span>

                                            <span class="badge bg-info-subtle text-info border">
                                                {{ $report['hold_installments'] }} Hold
                                            </span>

                                            <span class="badge bg-danger-subtle text-danger border">
                                                {{ $report['pending_installments'] }} Due
                                            </span>
                                        </div>

                                        <small class="text-muted">
                                            Due till date:
                                            {{ $report['due_till_date'] }} / {{ $report['total_installments'] }}
                                        </small>
                                    </td>

                                    <td>
                                        @if ($report['next_due_date'])
                                            <div class="fw-semibold">
                                                {{ $report['next_due_date']->format('d M Y') }}
                                            </div>
                                            <small class="text-muted">
                                                Start: {{ $report['start_date']->format('d M Y') }}
                                            </small>
                                        @else
                                            <span class="text-success fw-semibold">Completed</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                            {{ $report['status_label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No EMI due records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="5" class="text-end">Total</td>
                                <td class="text-end">
                                    ₹{{ number_format($reports->sum('monthly_emi'), 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    ₹{{ number_format($summary['total_due_amount'], 2) }}
                                </td>
                                <td>
                                    {{ $summary['pending_installments'] }} Pending EMI
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Plot Detail Modals --}}
        @foreach ($reports as $index => $report)
            <div class="modal fade" id="emiPlotModal{{ $index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">

                        <div class="modal-header bg-success text-white rounded-top-4">
                            <div>
                                <h5 class="modal-title fw-bold mb-0">
                                    EMI Plot Details - {{ $report['booking_id'] }}
                                </h5>
                                <small>
                                    {{ $report['customer_id'] }} - {{ $report['customer_name'] }}
                                </small>
                            </div>

                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Project</small>
                                        <div class="fw-bold">{{ $report['project'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Block</small>
                                        <div class="fw-bold">{{ $report['block'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Total Plot</small>
                                        <div class="fw-bold text-primary">
                                            {{ $report['plot_count'] }} Plot(s)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-success">
                                        <tr>
                                            <th>#</th>
                                            <th>Plot No</th>
                                            <th>Monthly EMI</th>
                                            <th>Due Amount</th>
                                            <th>Paid EMI</th>
                                            <th>Hold EMI</th>
                                            <th>Pending EMI</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach (explode(',', $report['plots']) as $plotKey => $plotNo)
                                            <tr>
                                                <td>{{ $plotKey + 1 }}</td>

                                                <td class="fw-bold">
                                                    {{ trim($plotNo) }}
                                                </td>

                                                <td>
                                                    ₹{{ number_format($report['monthly_emi'], 2) }}
                                                </td>

                                                <td class="fw-bold text-danger">
                                                    ₹{{ number_format($report['total_due_amount'], 2) }}
                                                </td>

                                                <td>
                                                    <span class="badge bg-success-subtle text-success border">
                                                        {{ $report['paid_installments'] }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="badge bg-info-subtle text-info border">
                                                        {{ $report['hold_installments'] }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger border">
                                                        {{ $report['pending_installments'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#emiStatusTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No EMI due records found.'
                }
            });
        });
    </script>
@endpush