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
                    <i class="bi bi-file-earmark-x me-2"></i>
                    Without Registered Plot Report
                </h3>

                <small class="text-muted">
                    Search and export pending registry reports
                </small>
            </div>

            <span class="badge badge-report">
                Total: {{ count($bookings) }}
            </span>

        </div>


        {{-- Filter --}}
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
                            <label class="fw-semibold mb-1">Customer ID</label>

                            <select name="customer_id" id="customer_id" class="form-select">

                                <option value="">All</option>

                                @foreach ($customerIds as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                        {{ $customer->customer_code }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">Customer Name</label>

                            <input type="text" name="customer_name" id="customer_name" class="form-control"
                                placeholder="Customer Name" value="{{ request('customer_name') }}" readonly>

                        </div>


                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">Project Name</label>

                            <select name="project_id" class="form-select">

                                <option value="">All</option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>

                                        {{ $project->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">Block</label>

                            <select name="block_id" class="form-select">

                                <option value="">All</option>

                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}"
                                        {{ request('block_id') == $block->id ? 'selected' : '' }}>

                                        {{ $block->block }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-12 d-flex gap-2 align-items-end">

                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.without-registered-plot-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset

                            </a>

                            <a href="{{ route('admin.without-registered-plot-report.export', request()->all()) }}"
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

            <div class="report-header">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-table me-2"></i>

                    Pending Registry Records

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="withoutRegistryTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>
                                <th>Sr.No</th>
                                <th>Booking Id</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>Total Cost</th>
                            </tr>

                        </thead>

                        <tbody>

                            @php
                                $grandTotal = 0;
                            @endphp

                            @foreach ($bookings as $key => $booking)
                                @php
                                    $totalCost = $booking->plotSaleDetail?->total_plot_cost ?? 0;
                                    $grandTotal += $totalCost;
                                @endphp

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $booking->booking_code ?? 'N/A' }}</td>

                                    <td>{{ $booking->primaryDetail?->name ?? 'N/A' }}</td>

                                    <td>{{ $booking->plotSaleDetail?->project?->name ?? 'N/A' }}</td>

                                    <td>{{ $booking->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}</td>

                                    <td>₹{{ number_format($totalCost, 2) }}</td>

                                </tr>
                            @endforeach

                        </tbody>


                        <tfoot>

                            <tr class="fw-bold table-footer">

                                <th colspan="5" class="text-end">
                                    Total
                                </th>

                                <th>
                                    ₹{{ number_format($grandTotal, 2) }}
                                </th>

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

            $('#withoutRegistryTable').DataTable({
                pageLength: 10,
                scrollX: true,
                ordering: false
            });

            function getCustomerName(customerId) {
                if (customerId != '') {
                    $.ajax({
                        url: "/admin/get-customer-details/" + customerId,
                        type: "GET",
                        success: function(response) {
                            $('#customer_name').val(response.name);
                        }
                    });
                } else {
                    $('#customer_name').val('');
                }
            }

            $('#customer_id').change(function() {
                getCustomerName($(this).val());
            });

            let customerId = $('#customer_id').val();

            if (customerId != '') {
                getCustomerName(customerId);
            }

        });
    </script>
@endpush
