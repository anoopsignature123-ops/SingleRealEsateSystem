@extends('layouts.app')
@push('title')
    Full Payment and One Time Payment Report
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="transaction-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon"><i class="bi bi-cash-stack text-success me-2"></i></span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Full Payment and One Time Payment
                            Report</span>
                        <h3 class="fw-bold text-dark mb-1">Full Payment and One Time Payment Report</h3>
                        <p class="text-muted small mb-0">Search and export payment dues reports.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('one-time-payment-dues-report.export', request()->all()) }}"
                        class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export
                    </a>
                </div>
            </div>
        </div>
        {{-- Summary --}}
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
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Payable</small>
                        <h4 class="fw-bold text-success mb-0">₹{{ number_format($summary['total_payable'], 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Paid</small>
                        <h4 class="fw-bold text-primary mb-0">₹{{ number_format($summary['total_paid'], 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Due</small>
                        <h4 class="fw-bold text-danger mb-0">₹{{ number_format($summary['total_due'], 2) }}</h4>
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
                        <small class="text-muted">Filter by customer code or customer name.</small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-4 col-md-6">
                            <label class="form-label fw-semibold">Select Customer</label>
                            <select name="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->customerBooking?->customer_id }}"
                                        {{ request('customer_id') == $item->customerBooking?->customer_id ? 'selected' : '' }}>
                                        {{ $item->customerBooking?->customer_code }} -
                                        {{ $item->customerBooking?->primaryDetail?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control"
                                value="{{ request('customer_name') }}" placeholder="Enter customer name">
                        </div>

                        <div class="col-xl-4 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i> Search
                            </button>

                            <a href="{{ route('one-time-payment-dues-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
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
                            Payment Due Records
                        </h5>
                        <small class="text-muted">Click Total Plot to view proper plot details.</small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $paymentGroups->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="paymentTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Total Plot</th>
                                <th class="text-end">Payable Amount</th>
                                <th class="text-end">Paid Amount</th>
                                <th class="text-end">Due Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($paymentGroups as $key => $payment)
                                @php
                                    $statusClass = $payment['status'] === 'completed' ? 'bg-success' : 'bg-danger';
                                    $statusLabel = $payment['status'] === 'completed' ? 'Completed' : 'Due';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-bold">{{ $payment['booking_code'] }}</div>
                                        <small class="text-muted">{{ $payment['customer_code'] }}</small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $payment['customer_name'] }}</div>
                                    </td>

                                    <td>{{ $payment['project'] }}</td>

                                    <td>{{ $payment['block'] }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            {{ $payment['plots'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#plotDetailsModal{{ $key }}">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ $payment['plot_count'] }} Plot(s)
                                        </button>
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($payment['payable'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-primary">
                                        ₹{{ number_format($payment['paid'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-danger">
                                        ₹{{ number_format($payment['due'], 2) }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No payment due records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="7" class="text-end">Total</td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_payable'], 2) }}
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

        {{-- Plot Detail Modals --}}
        @foreach ($paymentGroups as $key => $payment)
            <div class="modal fade" id="plotDetailsModal{{ $key }}" tabindex="-1"
                aria-labelledby="plotDetailsModalLabel{{ $key }}" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">

                        <div class="modal-header bg-success text-white rounded-top-4">
                            <div>
                                <h5 class="modal-title fw-bold" id="plotDetailsModalLabel{{ $key }}">
                                    Plot Details - {{ $payment['booking_code'] }}
                                </h5>
                                <small>
                                    {{ $payment['customer_code'] }} - {{ $payment['customer_name'] }}
                                </small>
                            </div>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Project</small>
                                        <div class="fw-bold">{{ $payment['project'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Total Plot</small>
                                        <div class="fw-bold text-primary">{{ $payment['plot_count'] }} Plot(s)</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Total Plot Amount</small>
                                        <div class="fw-bold text-success">
                                            ₹{{ number_format(collect($payment['plot_details'])->sum('amount'), 2) }}
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
                                            <th>Block</th>
                                            <th>Area</th>
                                            <th>Rate</th>
                                            <th class="text-end">Plot Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($payment['plot_details'] as $plotIndex => $plot)
                                            <tr>
                                                <td>{{ $plotIndex + 1 }}</td>
                                                <td class="fw-bold">{{ $plot['plot_no'] ?? 'N/A' }}</td>
                                                <td>{{ $plot['block'] ?? 'N/A' }}</td>
                                                <td>{{ $plot['area'] ?? 'N/A' }}</td>
                                                <td>
                                                    @if (!empty($plot['rate']))
                                                        ₹{{ number_format($plot['rate'], 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    ₹{{ number_format($plot['amount'] ?? 0, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    No plot details found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                    <tfoot>
                                        <tr class="fw-bold table-light">
                                            <td colspan="5" class="text-end">Total</td>
                                            <td class="text-end text-success">
                                                ₹{{ number_format(collect($payment['plot_details'])->sum('amount'), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
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
            $('#paymentTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No payment due records found.'
                }
            });
        });
    </script>
@endpush
