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

                    <i class="bi bi-house-check me-2"></i>

                    Plot Booking Details Report

                </h3>

                <small class="text-muted">

                    Search and export booking reports

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

                        <div class="col-md-4">
                            <label>Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">

                                <option value="">Select Customer ID</option>

                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->customer_code }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Customer Name</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control"
                                placeholder="Enter Customer Name" value="{{ request('customer_name') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Project Name</label>
                            <select name="project_id" id="project_id" class="form-select">

                                <option value="">Select Project</option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Select Block</label>
                            <select name="block_id" id="block_id" class="form-select">
                                <option value=""> Select Block</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Select PLC Type</label>
                            <select name="plot_type_id" id="plot_type_id" class="form-select">
                                <option value="">Select PLC Type</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Plan Type</label>
                            <select name="plan_type" class="form-select">

                                <option value="">Select Plan Type</option>

                                <option value="full_payment" {{ request('plan_type') == 'full_payment' ? 'selected' : '' }}>
                                    Full Payment
                                </option>

                                <option value="emi_plan" {{ request('plan_type') == 'emi_plan' ? 'selected' : '' }}>
                                    EMI Plan
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-select">

                                <option value="">Select Payment Mode</option>

                                <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>
                                    Cash
                                </option>

                                <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>
                                    Cheque
                                </option>

                                <option value="online" {{ request('payment_mode') == 'online' ? 'selected' : '' }}>
                                    Online
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>

                            <a href="{{ route('admin.plot-booking-details-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>

                                Reset

                            </a>

                            <a href="{{ route('admin.plot-booking-details-report.export', request()->all()) }}"
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

                    Booking Records

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="bookingTable" class="table table-hover align-middle nowrap w-100">

                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Agent Id/ Name</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>Plot Rate/ Area</th>
                                <th>Plot Cost</th>
                                <th>Other Charges</th>
                                <th>Discount</th>
                                <th>Final Amount</th>
                                <th>Paid Amount</th>
                                <th>Inst Amount</th>
                                <th>Booking Date</th>
                                <th>Plan Type</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($bookings as $key => $booking)
                                @php
                                    $plotSale = $booking->plotSaleDetail;
                                    $payment = $booking->payment;
                                    $paidAmount = $booking->payments->sum('booking_amount');
                                    $plotRate = $plotSale?->plot_rate ?? 0;
                                    $plotArea = $plotSale?->plot_area ?? 0;
                                    $installmentAmount = 0;

                                    if (
                                        ($payment?->plan_type ?? '') == 'emi_plan' &&
                                        ($payment?->emi_months ?? 0) > 0
                                    ) {
                                        $installmentAmount = $payment->net_payable_amount / $payment->emi_months;
                                    }
                                @endphp

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $booking->booking_code ?? 'N/A' }}</td>

                                    <td>
                                        {{ $booking->associate_code ?? 'N/A' }}
                                        /
                                        {{ $booking->associate_name ?? 'N/A' }}
                                    </td>

                                    <td>{{ $booking->primaryDetail?->name ?? 'N/A' }}</td>

                                    <td>{{ $plotSale?->project?->name ?? 'N/A' }}</td>

                                    <td>{{ $plotSale?->plotDetail?->plot_number ?? 'N/A' }}</td>

                                    <td>
                                        ₹{{ number_format($plotRate, 2) }}/{{ $plotArea }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($plotSale?->plot_cost ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($plotSale?->other_charges ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($plotSale?->coupon_discount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($plotSale?->total_plot_cost ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($paidAmount, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($installmentAmount, 2) }}
                                    </td>

                                    <td>
                                        {{ $plotSale?->booking_date ? \Carbon\Carbon::parse($plotSale->booking_date)->format('d-m-Y') : 'N/A' }}
                                    </td>

                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $payment?->plan_type ?? 'N/A')) }}
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

            $('#bookingTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

            // Customer Auto Name
            $('#customer_id').change(function() {

                let customerId = $(this).val();

                if (customerId) {

                    $.get('/admin/plot-booking-details-report/customer-details/' + customerId,
                        function(response) {

                            $('#customer_name').val(response.name);

                        });

                }

            });

            // Project -> Block
            $('#project_id').change(function() {

                let projectId = $(this).val();

                $('#block_id').html('<option value="">All</option>');
                $('#plot_type_id').html('<option value="">All</option>');

                $.get('/admin/plot-booking-details-report/project-blocks/' + projectId,
                    function(response) {

                        $.each(response, function(index, block) {

                            $('#block_id').append(
                                `<option value="${block.id}">
                                ${block.block}
                            </option>`
                            );

                        });

                    });

            });

            // Block -> PLC
            $('#block_id').change(function() {

                let blockId = $(this).val();

                $('#plot_type_id').html('<option value="">All</option>');

                $.get('/admin/plot-booking-details-report/block-plc/' + blockId,
                    function(response) {

                        $.each(response, function(index, item) {

                            $('#plot_type_id').append(
                                `<option value="${item.id}">
                                ${item.plot_type_name}
                            </option>`
                            );

                        });

                    });

            });

        });
    </script>
@endpush
