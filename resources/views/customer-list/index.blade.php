@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center"
                            style="width:56px;height:56px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>

                        <div>
                            <h3 class="fw-bold mb-1 text-dark">Customer List</h3>
                            <p class="text-muted mb-0 small">
                                View customers and their booked plot summary.
                            </p>
                        </div>
                    </div>

                    <div class="badge bg-light text-dark border rounded-pill px-3 py-2">
                        Total Customers: {{ $customers->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer Table --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerListTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer ID</th>
                                <th>Reference</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th class="text-center">Bookings</th>
                                <th class="text-center">Plots</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customers as $key => $customer)
                                @php
                                    $primary = $customer->primaryDetail;
                                    $contact = $primary?->correspondenceDetail;

                                    $address = $primary?->permanent_address ??
                                        ($primary?->city ? $primary->city . ', ' . $primary->state : 'N/A');

                                    $parentCustomer = $customer->parentCustomer;
                                    $plots = $customer->booked_plots ?? collect();
                                @endphp

                                <tr>
                                    <td></td>

                                    <td>
                                        <span class="fw-bold text-dark">
                                            {{ $customer->customer_code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($parentCustomer)
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                                {{ $parentCustomer->customer_code }}
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                Self
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ ucfirst($primary?->name ?? 'N/A') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $customer->customer_type ? ucwords(str_replace('_', ' ', $customer->customer_type)) : '' }}
                                        </small>
                                    </td>

                                    <td style="max-width: 280px;">
                                        <span class="text-muted d-inline-block text-truncate"
                                            style="max-width: 270px;"
                                            title="{{ $address }}">
                                            {{ $address }}
                                        </span>
                                    </td>

                                    <td>{{ $contact?->telephone_no ?? 'N/A' }}</td>

                                    <td>
                                        <span class="text-muted">
                                            {{ $contact?->email ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                            {{ $plots->count() }}
                                            {{ $plots->count() > 1 ? 'Plots' : 'Plot' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-success rounded-pill px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#plotModal{{ $customer->id }}">
                                            <i class="bi bi-eye me-1"></i>
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-people fs-1 d-block mb-2 text-muted"></i>
                                        No customers found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @foreach ($customers as $customer)
            @php
                $primary = $customer->primaryDetail;
                $plots = $customer->booked_plots ?? collect();
            @endphp

            <div class="modal fade" id="plotModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable plot-modal-dialog">
                    <div class="modal-content border-0 rounded-4 shadow overflow-hidden">

                        <div class="modal-header border-0 px-4 py-3 plot-modal-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                                    style="width:42px;height:42px;">
                                    <i class="bi bi-grid-3x3-gap fs-5"></i>
                                </div>

                                <div>
                                    <h6 class="modal-title fw-bold mb-1">
                                        Booked Plot Details
                                    </h6>
                                    <small class="text-muted">
                                        {{ $customer->customer_code ?? 'N/A' }} - {{ $primary?->name ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-4">

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="plot-info-box">
                                        <small>Customer ID</small>
                                        <span>{{ $customer->customer_code ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="plot-info-box">
                                        <small>Customer Name</small>
                                        <span>{{ $primary?->name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="plot-info-box">
                                        <small>Total Bookings</small>
                                        <span>{{ $plots->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($plots->count() > 0)
                                <div class="table-responsive modal-table-scroll">
                                    <table class="table table-hover align-middle mb-0 plot-modal-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Booking ID</th>
                                                <th>Project</th>
                                                <th>Block</th>
                                                <th>Plot No</th>
                                                <th>Plot Area</th>
                                                <th>Plot Rate</th>
                                                <th>Total Cost</th>
                                                <th>Booking Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($plots as $plotKey => $plot)
                                                <tr>
                                                    <td>{{ $plotKey + 1 }}</td>

                                                    <td>
                                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
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
                                                            ₹{{ number_format((float) ($plot->total_plot_cost ?? $plot->total_amount ?? 0), 2) }}
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
                                    No booked plot found.
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer border-0 px-4 py-3">
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

@push('styles')
    <style>
        .plot-modal-dialog {
            max-width: 980px;
        }

        .plot-modal-header {
            background: #f8fafc;
        }

        .plot-info-box {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 12px 14px;
            background: #f8fafc;
        }

        .plot-info-box small {
            display: block;
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .plot-info-box span {
            font-weight: 700;
            font-size: 13px;
            color: #111827;
        }

        .modal-table-scroll {
            max-height: 340px;
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }

        .plot-modal-table {
            white-space: nowrap;
            font-size: 13px;
        }

        .plot-modal-table thead th {
            background: #198754;
            color: #fff;
            font-size: 12px;
            padding: 12px;
            text-transform: uppercase;
        }

        .plot-modal-table tbody td {
            padding: 11px 12px;
        }

        #customerListTable th,
        #customerListTable td {
            vertical-align: middle;
        }

        #customerListTable thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 700;
            color: #475569;
        }

        #customerListTable tbody td {
            padding-top: 14px;
            padding-bottom: 14px;
        }

        @media (max-width: 991px) {
            .plot-modal-dialog {
                max-width: 95%;
                margin: 12px auto;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#customerListTable tbody tr td').attr('colspan') == undefined) {
                let table = $('#customerListTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    columnDefs: [{
                        targets: 0,
                        orderable: false,
                        searchable: false
                    }]
                });

                table.on('order.dt search.dt draw.dt', function() {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = '#' + (i + 1);
                    });
                }).draw();
            }
        });
    </script>
@endpush