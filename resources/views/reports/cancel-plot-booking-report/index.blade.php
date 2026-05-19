@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Heading --}}
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">

            <div>

                <h3 class="report-title">
                    <i class="fas fa-ban text-danger me-2"></i>
                    Cancel Plot Booking Report
                </h3>

                <small class="report-subtitle">
                    Search and export cancelled plot booking reports
                </small>

            </div>

            <span class="badge bg-primary badge-report">
                Total: {{ count($cancelBookings) }}
            </span>

        </div>


        {{-- Filter Card --}}
        <div class="card report-card mb-4">

            <div class="report-header">

                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-filter text-success me-2"></i>
                    Filter Report
                </h5>

            </div>

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3">

                        {{-- Customer --}}
                        <div class="col-md-4">

                            <label class="fw-semibold mb-1">
                                Customer ID
                            </label>

                            <select name="customer_id" id="customer_id" class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach ($customerIds as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                        {{ $customer->customer_code }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- Customer Name --}}
                        <div class="col-md-4">

                            <label class="fw-semibold mb-1">
                                Customer Name
                            </label>

                            <input type="text" id="customer_name" class="form-control" readonly
                                placeholder="Enter Customer Name">

                        </div>


                        {{-- Project --}}
                        <div class="col-md-4">

                            <label class="fw-semibold mb-1">
                                Project
                            </label>

                            <select name="project_id" class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>

                                        {{ $project->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- Block --}}
                        <div class="col-md-4">

                            <label class="fw-semibold mb-1">
                                Block
                            </label>

                            <select name="block_id" class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}"
                                        {{ request('block_id') == $block->id ? 'selected' : '' }}>

                                        {{ $block->block }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- Buttons --}}
                        <div class="col-md-4 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.cancel-plot-booking-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset

                            </a>

                            <a href="{{ route('admin.cancel-plot-booking-report.export', request()->all()) }}"
                                class="btn btn-success">

                                <i class="fas fa-file-excel me-1"></i>
                                Export

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Table Card --}}
        <div class="card report-card">

            <div class="report-header">

                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table text-success me-2"></i>
                    Report Data
                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="cancelBookingTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>

                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot</th>
                                <th>Deduction</th>
                                <th>Refund</th>
                                <th>Cancel Date</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($cancelBookings as $key => $item)
                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $item->customerBooking?->booking_code }}</td>

                                    <td>{{ $item->customerBooking?->primaryDetail?->name }}</td>

                                    <td>{{ $item->plotSaleDetail?->project?->name }}</td>

                                    <td>{{ $item->plotSaleDetail?->block?->block }}</td>

                                    <td>{{ $item->plotSaleDetail?->plotDetail?->plot_number }}</td>

                                    <td>
                                        ₹{{ number_format($item->deduction_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($item->refund_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->created_at?->format('d-m-Y') }}
                                    </td>

                                </tr>
                            @endforeach

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

            $('#customer_id').select2({
                width: '100%'
            });

            $('#cancelBookingTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

        });
    </script>
@endpush
