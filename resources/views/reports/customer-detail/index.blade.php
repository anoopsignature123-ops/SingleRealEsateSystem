@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-people me-2"></i>
                    Customer Detail Report
                </h3>
                <small class="report-subtitle">
                    Search and export customer reports
                </small>
            </div>

            <span class="badge bg-primary badge-report">
                Total: {{ count($customers) }}
            </span>
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

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="fw-semibold">
                                Customer Name
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ request('name') }}"
                                class="form-control"
                                placeholder="Enter customer name">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold">
                                Mobile
                            </label>

                            <input type="text"
                                name="mobile"
                                value="{{ request('mobile') }}"
                                class="form-control"
                                placeholder="Enter mobile number">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold">
                                From Date
                            </label>

                            <input type="date"
                                name="from_date"
                                value="{{ request('from_date') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold">
                                To Date
                            </label>

                            <input type="date"
                                name="to_date"
                                value="{{ request('to_date') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3 d-flex gap-2 align-items-end">

                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.customer-details-report.index') }}"
                                class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('admin.customer-details-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Export
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- Table Section --}}
        <div class="card report-card">

            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Customer Records
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="customerReportTable"
                        class="table table-hover align-middle nowrap w-100">

                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Reference</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Status</th>
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

                                    <td>{{ $customer->customer_code }}</td>

                                    <td>
                                        @if ($customer->parentCustomer)
                                            <span class="badge border border-info text-info">
                                                {{ $customer->parentCustomer->customer_code }}
                                            </span>
                                        @else
                                            Self
                                        @endif
                                    </td>

                                    <td>
                                        {{ $primary?->name ?? 'N/A' }}
                                    </td>

                                    <td title="{{ $address }}">
                                        {{ $address }}
                                    </td>

                                    <td>
                                        {{ $contact?->telephone_no ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $contact?->email ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <span class="badge border border-info text-info">
                                            Booked {{ $customer->total_bookings }} Plot
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center">
                                        No Record Found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            $('#customerReportTable').DataTable({
                pageLength: 10,
                scrollX: true,
                responsive: true
            });

        });
    </script>
@endpush