@extends('layouts.app')

@push('title')
    Plot Booking Details Report
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
                        <i class="bi bi-house-check text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Plot Booking Details Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            Plot Booking Details Report
                        </h3>
                        <p class="text-muted small mb-0">
                            Search and export booking reports with multiple plot details.
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('plot-booking-details-report.export', request()->all()) }}"
                        class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Bookings</small>
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
                        <small class="text-muted fw-semibold">Final Amount</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_final_amount'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Due Amount</small>
                        <h4 class="fw-bold text-danger mb-0">
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
                            Filter booking report by customer, project, block, PLC, plan and date.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->customer_code }} -
                                        {{ $item->primaryDetail?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control"
                                placeholder="Enter customer name" value="{{ request('customer_name') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Project Name</label>
                            <select name="project_id" id="project_id" class="form-select">
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
                            <select name="block_id" id="block_id" class="form-select"
                                data-selected="{{ $selectedBlockId }}">
                                <option value="">All Blocks</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">PLC Type</label>
                            <select name="plot_type_id" id="plot_type_id" class="form-select"
                                data-selected="{{ $selectedPlotTypeId }}">
                                <option value="">All PLC Types</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Plan Type</label>
                            <select name="plan_type" class="form-select">
                                <option value="">All Plans</option>
                                <option value="full_payment"
                                    {{ request('plan_type') == 'full_payment' ? 'selected' : '' }}>
                                    Full Payment
                                </option>
                                <option value="emi_plan" {{ request('plan_type') == 'emi_plan' ? 'selected' : '' }}>
                                    EMI Plan
                                </option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Payment Mode</label>

                            <select name="payment_mode" class="form-select">
                                <option value="">All Modes</option>
                                <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>Cheque
                                </option>
                                <option value="dd" {{ request('payment_mode') == 'dd' ? 'selected' : '' }}>DD</option>
                                <option value="neft_rtgs" {{ request('payment_mode') == 'neft_rtgs' ? 'selected' : '' }}>
                                    NEFT / RTGS</option>
                                <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Card
                                </option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control"
                                value="{{ request('from_date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('plot-booking-details-report.index') }}"
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
                            Booking Records
                        </h5>
                        <small class="text-muted">
                            Click Total Plot to view all plot details.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $bookingGroups->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="bookingTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Agent</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Total Plot</th>
                                <th class="text-end">Final Amount</th>
                                <th class="text-end">Paid Amount</th>
                                <th class="text-end">Due Amount</th>
                                <th class="text-end">Inst Amount</th>
                                <th>Booking Date</th>
                                <th>Plan Type</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bookingGroups as $key => $booking)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-bold">{{ $booking['booking_code'] }}</div>
                                        <small class="text-muted">{{ $booking['customer_code'] }}</small>
                                    </td>

                                    <td>{{ $booking['agent'] }}</td>

                                    <td>
                                        <div class="fw-semibold">{{ $booking['customer_name'] }}</div>
                                    </td>

                                    <td>{{ $booking['project'] }}</td>

                                    <td>{{ $booking['block'] }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            {{ $booking['plots'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#plotDetailsModal{{ $key }}">
                                            <i class="bi bi-eye me-1"></i>
                                            {{ $booking['plot_count'] }} Plot(s)
                                        </button>
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($booking['final_amount'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-primary">
                                        ₹{{ number_format($booking['paid_amount'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold text-danger">
                                        ₹{{ number_format($booking['due_amount'], 2) }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        ₹{{ number_format($booking['installment_amount'], 2) }}
                                    </td>

                                    <td>{{ $booking['booking_date'] }}</td>

                                    <td>
                                        <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                                            {{ $booking['plan_type_label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No booking records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="8" class="text-end">Total</td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_final_amount'], 2) }}
                                </td>
                                <td class="text-end text-primary">
                                    ₹{{ number_format($summary['total_paid_amount'], 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    ₹{{ number_format($summary['total_due_amount'], 2) }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Plot Detail Modals --}}
        @foreach ($bookingGroups as $key => $booking)
            <div class="modal fade" id="plotDetailsModal{{ $key }}" tabindex="-1"
                aria-labelledby="plotDetailsModalLabel{{ $key }}" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow">

                        <div class="modal-header bg-success text-white rounded-top-4">
                            <div>
                                <h5 class="modal-title fw-bold" id="plotDetailsModalLabel{{ $key }}">
                                    Plot Details - {{ $booking['booking_code'] }}
                                </h5>
                                <small>
                                    {{ $booking['customer_code'] }} - {{ $booking['customer_name'] }}
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
                                        <div class="fw-bold">{{ $booking['project'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Total Plot</small>
                                        <div class="fw-bold text-primary">{{ $booking['plot_count'] }} Plot(s)</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded-4 p-3 bg-light">
                                        <small class="text-muted fw-semibold">Final Amount</small>
                                        <div class="fw-bold text-success">
                                            ₹{{ number_format($booking['final_amount'], 2) }}
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
                                            <th>PLC Type</th>
                                            <th>Area</th>
                                            <th>Rate</th>
                                            <th class="text-end">Plot Cost</th>
                                            <th class="text-end">Other Charges</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Final Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($booking['plot_details'] as $plotIndex => $plot)
                                            <tr>
                                                <td>{{ $plotIndex + 1 }}</td>
                                                <td class="fw-bold">{{ $plot['plot_no'] }}</td>
                                                <td>{{ $plot['block'] }}</td>
                                                <td>{{ $plot['plot_type'] }}</td>
                                                <td>{{ $plot['area'] }}</td>
                                                <td>₹{{ number_format($plot['rate'], 2) }}</td>
                                                <td class="text-end">₹{{ number_format($plot['plot_cost'], 2) }}</td>
                                                <td class="text-end">₹{{ number_format($plot['other_charges'], 2) }}</td>
                                                <td class="text-end">₹{{ number_format($plot['discount'], 2) }}</td>
                                                <td class="text-end fw-bold text-success">
                                                    ₹{{ number_format($plot['final_amount'], 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="fw-bold table-light">
                                            <td colspan="6" class="text-end">Total</td>
                                            <td class="text-end">
                                                ₹{{ number_format(collect($booking['plot_details'])->sum('plot_cost'), 2) }}
                                            </td>
                                            <td class="text-end">
                                                ₹{{ number_format(collect($booking['plot_details'])->sum('other_charges'), 2) }}
                                            </td>
                                            <td class="text-end">
                                                ₹{{ number_format(collect($booking['plot_details'])->sum('discount'), 2) }}
                                            </td>
                                            <td class="text-end text-success">
                                                ₹{{ number_format(collect($booking['plot_details'])->sum('final_amount'), 2) }}
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
            $('#bookingTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No booking records found.'
                }
            });

            $('#customer_id').change(function() {
                let customerId = $(this).val();

                if (customerId) {
                    $.get('/plot-booking-details-report/customer-details/' + customerId, function(
                    response) {
                        $('#customer_name').val(response.name);
                    });
                } else {
                    $('#customer_name').val('');
                }
            });

            function loadBlocks(projectId, selectedBlockId = '') {
                $('#block_id').html('<option value="">All Blocks</option>');
                $('#plot_type_id').html('<option value="">All PLC Types</option>');

                if (!projectId) {
                    return;
                }

                $.get('/plot-booking-details-report/project-blocks/' + projectId, function(response) {
                    $.each(response, function(index, block) {
                        let selected = String(selectedBlockId) === String(block.id) ? 'selected' :
                            '';

                        $('#block_id').append(
                            `<option value="${block.id}" ${selected}>${block.block}</option>`
                        );
                    });

                    if (selectedBlockId) {
                        loadPlcTypes(selectedBlockId, $('#plot_type_id').data('selected'));
                    }
                });
            }

            function loadPlcTypes(blockId, selectedPlotTypeId = '') {
                $('#plot_type_id').html('<option value="">All PLC Types</option>');

                if (!blockId) {
                    return;
                }

                $.get('/plot-booking-details-report/block-plc/' + blockId, function(response) {
                    $.each(response, function(index, item) {
                        let selected = String(selectedPlotTypeId) === String(item.id) ? 'selected' :
                            '';

                        $('#plot_type_id').append(
                            `<option value="${item.id}" ${selected}>${item.plot_type_name}</option>`
                        );
                    });
                });
            }

            $('#project_id').change(function() {
                loadBlocks($(this).val());
            });

            $('#block_id').change(function() {
                loadPlcTypes($(this).val());
            });

            let selectedProjectId = $('#project_id').val();
            let selectedBlockId = $('#block_id').data('selected');

            if (selectedProjectId) {
                loadBlocks(selectedProjectId, selectedBlockId);
            }
        });
    </script>
@endpush
