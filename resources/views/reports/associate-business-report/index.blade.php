@extends('layouts.app')

@push('title')
    Associate Business Report
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
                        <i class="bi bi-graph-up text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Associate Business Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">Associate Business Report</h3>
                        <p class="text-muted small mb-0">
                            Associate wise booking business, paid amount and due amount report.
                        </p>
                    </div>
                </div>

                <a href="{{ route('associate-business-report.export', request()->query()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
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
                        <small class="text-muted fw-semibold">Total Business</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_business'], 2) }}
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

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Filter Report</h5>
                        <small class="text-muted">Filter associate business by associate and booking date.</small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-4 col-md-6">
                            <label class="form-label fw-semibold">Associate</label>
                            <select name="associate_id" class="form-select">
                                <option value="">All Associates</option>
                                @foreach ($associates as $associate)
                                    <option value="{{ $associate->id }}"
                                        {{ request('associate_id') == $associate->id ? 'selected' : '' }}>
                                        {{ $associate->associate_id }} - {{ $associate->associate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-xl-2 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('associate-business-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
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
                            Associate Business Records
                        </h5>
                        <small class="text-muted">
                            Multiple plot bookings are grouped by booking code.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $reports->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="associateBusinessTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Associate</th>
                                <th>Customer</th>
                                <th>Booking ID</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Total Plot</th>
                                <th class="text-end">Business</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-bold">{{ $report['associate_id'] }}</div>
                                        <small class="text-muted">{{ $report['associate_name'] }}</small>
                                    </td>

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

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($report['total_business'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-primary">
                                        ₹{{ number_format($report['paid_amount'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-danger">
                                        ₹{{ number_format($report['due_amount'], 2) }}
                                    </td>

                                    <td>{{ $report['booking_date'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No associate business records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="8" class="text-end">Total</td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_business'], 2) }}
                                </td>
                                <td class="text-end text-primary">
                                    ₹{{ number_format($summary['total_paid'], 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    ₹{{ number_format($summary['total_due'], 2) }}
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
            $('#associateBusinessTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No associate business records found.'
                }
            });
        });
    </script>
@endpush
