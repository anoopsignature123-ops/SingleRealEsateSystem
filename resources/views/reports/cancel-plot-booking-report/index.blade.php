@extends('layouts.app')

@push('title')
    Cancel Plot Booking Report
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
                        <i class="bi bi-x-octagon text-danger"></i>
                    </span>

                    <div>
                        <span class="text-danger fw-bold text-uppercase small">
                            Cancel Plot Booking Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            Cancel Plot Booking Report
                        </h3>
                        <p class="text-muted small mb-0">
                            Search and export cancelled plot booking reports.
                        </p>
                    </div>
                </div>

                <a href="{{ route('cancel-plot-booking-report.export', request()->all()) }}"
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
                        <small class="text-muted fw-semibold">Total Cancelled</small>
                        <h4 class="fw-bold mb-0">{{ $summary['total_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Projects</small>
                        <h4 class="fw-bold text-primary mb-0">{{ $summary['total_projects'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-warning-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Deduction</small>
                        <h4 class="fw-bold text-warning mb-0">
                            ₹{{ number_format($summary['total_deduction'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Refund</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_refund'], 2) }}
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
                            Filter cancelled booking by customer, project and block.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customerIds as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }} -
                                        {{ $customer->primaryDetail?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Project</label>
                            <select name="project_id" class="form-select">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Block</label>
                            <select name="block_id" class="form-select">
                                <option value="">All Blocks</option>
                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}"
                                        {{ request('block_id') == $block->id ? 'selected' : '' }}>
                                        {{ $block->block }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('cancel-plot-booking-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- DataTable --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-table text-success me-2"></i>
                            Cancel Booking Records
                        </h5>
                        <small class="text-muted">
                            Complete list of cancelled plot bookings.
                        </small>
                    </div>

                    <span class="badge bg-danger-subtle text-danger border rounded-pill px-3 py-2">
                        {{ $cancelBookings->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="cancelBookingTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot</th>
                                <th class="text-end">Deduction</th>
                                <th class="text-end">Refund</th>
                                <th>Pay Mode</th>
                                <th>Cancel Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($cancelBookings as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-bold">
                                            {{ $item->customerBooking?->booking_code ?? 'N/A' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $item->customerBooking?->customer_code ?? 'N/A' }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $item->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td>{{ $item->plotSaleDetail?->project?->name ?? 'N/A' }}</td>

                                    <td>{{ $item->plotSaleDetail?->block?->block ?? 'N/A' }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            {{ $item->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-end fw-bold text-warning">
                                        ₹{{ number_format($item->deduction_amount ?? 0, 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($item->refund_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-2">
                                            {{ strtoupper($item->pay_mode ?? 'N/A') }}
                                        </span>
                                    </td>

                                    <td>{{ $item->created_at?->format('d-m-Y') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No cancel booking records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="6" class="text-end">Total</td>
                                <td class="text-end text-warning">
                                    ₹{{ number_format($summary['total_deduction'], 2) }}
                                </td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_refund'], 2) }}
                                </td>
                                <td colspan="2"></td>
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
            $('#customer_id').select2({
                width: '100%'
            });

            $('#cancelBookingTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No cancel booking records found.'
                }
            });
        });
    </script>
@endpush