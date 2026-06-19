@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

    <style>
        .customer-modal-dialog {
            max-width: 1050px;
        }

        .customer-modal-content {
            border-radius: 22px;
            overflow: hidden;
        }

        .customer-modal-header {
            background: linear-gradient(135deg, #f0fdf4, #ffffff);
            border-bottom: 1px solid #d8f3e4;
            padding: 18px 22px;
        }

        .customer-modal-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: rgba(25, 135, 84, .12);
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .customer-info-box {
            border: 1px solid #dfeee6;
            background: #fbfffd;
            border-radius: 16px;
            padding: 12px 14px;
        }

        .customer-info-box small {
            color: #6b7280;
            font-size: 11px;
            display: block;
            margin-bottom: 3px;
        }

        .customer-info-box span {
            font-weight: 700;
            color: #111827;
            font-size: 13px;
        }

        .plot-modal-table-wrap {
            max-height: 330px;
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
        }

        .plot-modal-table {
            font-size: 13px;
            white-space: nowrap;
            margin-bottom: 0;
        }

        .plot-modal-table thead th {
            background: #198754;
            color: #fff;
            font-size: 12px;
            text-transform: uppercase;
            padding: 12px;
        }

        .plot-modal-table tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        .plot-badge {
            background: #ecfdf5;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 50px;
            padding: 6px 12px;
            font-weight: 700;
            font-size: 12px;
        }

        .modal-footer-soft {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 991px) {
            .customer-modal-dialog {
                max-width: 96%;
                margin: 12px auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-4">
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center"
                            style="width:56px;height:56px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>

                        <div>
                            <h3 class="fw-bold mb-1 text-dark"> Customer List Report</h3>
                            <p class="text-muted mb-0 small">
                                Search and export customer reports
                            </p>
                        </div>
                    </div>

                    <div class="badge bg-light text-dark border rounded-pill px-3 py-2">
                        Total Customers: {{ $customers->count() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- Filter Section --}}
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Report
                </h5>
            </div>

            <div class="card-body">
                <form method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="fw-semibold mb-1">Customer Name</label>
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                placeholder="Enter customer name">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">Mobile</label>
                            <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control"
                                placeholder="Enter mobile number">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-md-3 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('customer-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('customer-details-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Export
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card report-card">
            <div class="report-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Customer Records
                </h5>

                <small>Showing booked customer records</small>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="customerReportTable" class="table table-hover align-middle nowrap w-100 table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Reference</th>
                                <th>Customer Name</th>
                                 <th>Mobile Number</th>
                                <th>Customer Email</th>
                                <th>Address</th>
                                <th>Total Plot</th>
                                 
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($customers as $key => $customer)
                                @php
                                    $primary = $customer->primaryDetail;
                                    $contact = $primary?->correspondenceDetail;
                                    $address = $primary?->permanent_address ?? 'N/A';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="fw-bold">
                                            {{ $customer->customer_code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($customer->parentCustomer)
                                            <span class="badge border border-info text-info rounded-pill px-3 py-2">
                                                {{ $customer->parentCustomer->customer_code }}
                                            </span>
                                        @else
                                             <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                Self
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ $primary?->name ?? 'N/A' }}</td>

                                    <td>+91 {{ $contact?->telephone_no ?? 'N/A' }}</td>

                                    <td>{{ $contact?->email ?? 'N/A' }}</td>
                                    <td title="{{ $address }}">
                                        {{ \Illuminate\Support\Str::limit($address, 40) }}
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="badge bg-light text-dark border rounded-pill px-3 py-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#plotModal{{ $key }}">
                                            {{ $customer->total_bookings ?? 0 }} Plot
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No Record Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ALL MODALS --}}
        @foreach ($customers as $key => $customer)
            @php
                $primary = $customer->primaryDetail;
                $bookedPlots = $customer->booked_plots ?? collect();
            @endphp

            <div class="modal fade" id="plotModal{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable customer-modal-dialog">
                    <div class="modal-content border-0 shadow customer-modal-content">

                        <div class="modal-header customer-modal-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="customer-modal-icon">
                                    <i class="bi bi-grid-3x3-gap fs-5"></i>
                                </div>

                                <div>
                                    <h5 class="modal-title fw-bold mb-1">
                                        Booked Plot Details
                                    </h5>
                                    <small class="text-muted">
                                        {{ $customer->customer_code ?? 'N/A' }} - {{ $primary?->name ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="customer-info-box">
                                        <small>Customer ID</small>
                                        <span>{{ $customer->customer_code ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="customer-info-box">
                                        <small>Customer Name</small>
                                        <span>{{ $primary?->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="customer-info-box">
                                        <small>Total Bookings</small>
                                        <span>{{ $bookedPlots->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($bookedPlots->count() > 0)
                                <div class="table-responsive plot-modal-table-wrap">
                                    <table class="table table-hover align-middle plot-modal-table">
                                        <thead>
                                            <tr>
                                                <th>Sr.No</th>
                                                <th>Booking No</th>
                                                <th>Project</th>
                                                <th>Block</th>
                                                <th>Plot No</th>
                                                <th>Area</th>
                                                <th>Rate</th>
                                                <th>Total Cost</th>
                                                <th>Booking Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($bookedPlots as $plotKey => $plot)
                                                <tr>
                                                    <td>{{ $plotKey + 1 }}</td>

                                                    <td>
                                                        <span class="plot-badge">
                                                            {{ $plot->booking_code ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <td>{{ $plot->project?->name ?? 'N/A' }}</td>

                                                    <td>{{ $plot->block?->block ?? $plot->block?->name ?? 'N/A' }}</td>

                                                    <td>
                                                        <span class="fw-bold text-success">
                                                            {{ $plot->plotDetail?->plot_number ?? $plot->plotDetail?->plot_no ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ $plot->plot_area ?? $plot->plotDetail?->plot_area ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        ₹{{ number_format((float) ($plot->plot_rate ?? $plot->rate ?? 0), 2) }}
                                                    </td>

                                                    <td>
                                                        <span class="fw-bold">
                                                            ₹{{ number_format((float) ($plot->total_amount ?? $plot->total_plot_cost ?? 0), 2) }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ $plot->booking_date ? \Carbon\Carbon::parse($plot->booking_date)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No Plot Found
                                </div>
                            @endif

                        </div>

                        <div class="modal-footer modal-footer-soft px-4 py-3">
                            <button type="button"
                                class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">
                                Close
                            </button>
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
            $('#customerReportTable').DataTable({
                pageLength: 10,
                scrollX: true,
                responsive: false
            });
        });
    </script>
@endpush