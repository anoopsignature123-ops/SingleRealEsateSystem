@extends('layouts.app')

@push('title')
    Customer Ledger Report
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
                        <i class="bi bi-journal-text text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Customer Ledger Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            Customer Ledger Report
                        </h3>
                        <p class="text-muted small mb-0">
                            View customer ledger, booking details and payment history.
                        </p>
                    </div>
                </div>

                @if ($ledger)
                    <a href="{{ route('customer-ledger-report.export', request()->all()) }}"
                        class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Export
                    </a>
                @endif
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
                            Select project, block, customer and plot to view ledger.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Project Name</label>
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

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Block</label>
                            <select name="block_id" id="block_id" class="form-select"
                                data-selected="{{ request('block_id') }}">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select"
                                data-selected="{{ request('customer_id') }}">
                                <option value="">Select Customer</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Plot No</label>
                            <select name="plot_id" id="plot_id" class="form-select"
                                data-selected="{{ request('plot_id') }}">
                                <option value="">Select Plot</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Booking ID</label>
                            <input type="text" id="booking_id" name="booking_id" class="form-control"
                                value="{{ request('booking_id') }}" placeholder="Booking ID" readonly>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('customer-ledger-report.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($ledger)
            @php
                $totalPaid = $ledger->payments->whereIn('payment_status', ['paid', 'cleared'])->sum('paid_amount');
                $totalHold = $ledger->payments->where('payment_status', 'hold')->sum('paid_amount');
                $totalCost = $ledger->plotSaleDetail?->total_plot_cost ?? ($ledger->payment?->net_payable_amount ?? 0);
                $totalDue = max(0, $totalCost - $totalPaid);
                $bookingAmount = $ledger->payment?->booking_amount ?? 0;
            @endphp

            {{-- Ledger Profile Header --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <small class="text-success fw-bold text-uppercase">Ledger Account</small>
                            <h4 class="fw-bold mb-1">{{ $ledger->primaryDetail?->name ?? 'N/A' }}</h4>
                            <div class="text-muted small">
                                Customer ID: <strong>{{ $ledger->customer_code ?? 'N/A' }}</strong>
                                |
                                Booking ID: <strong>{{ $ledger->booking_code ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="text-end">
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                {{ ucfirst(str_replace('_', ' ', $ledger->payment?->plan_type ?? 'N/A')) }}
                            </span>
                            <div class="small text-muted mt-2">
                                Mobile: {{ $ledger->primaryDetail?->correspondenceDetail?->mobile_number ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Amount Summary --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Total Plot Cost</small>
                            <h4 class="fw-bold mb-0">₹{{ number_format($totalCost, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Total Paid</small>
                            <h4 class="fw-bold text-success mb-0">₹{{ number_format($totalPaid, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-warning-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Hold Amount</small>
                            <h4 class="fw-bold text-warning mb-0">₹{{ number_format($totalHold, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border border-danger-subtle shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <small class="text-muted fw-semibold">Due Amount</small>
                            <h4 class="fw-bold text-danger mb-0">₹{{ number_format($totalDue, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ledger Details --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-person-vcard text-success me-2"></i>
                                Customer Details
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Customer Name</small>
                                    <div class="fw-bold">{{ $ledger->primaryDetail?->name ?? 'N/A' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Customer ID</small>
                                    <div class="fw-bold">{{ $ledger->customer_code ?? 'N/A' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Mobile</small>
                                    <div class="fw-bold">
                                        {{ $ledger->primaryDetail?->correspondenceDetail?->mobile_number ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Booking ID</small>
                                    <div class="fw-bold">{{ $ledger->booking_code ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-grid-3x3-gap text-success me-2"></i>
                                Plot Details
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted">Project</small>
                                    <div class="fw-bold">{{ $ledger->plotSaleDetail?->project?->name ?? 'N/A' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Block</small>
                                    <div class="fw-bold">{{ $ledger->plotSaleDetail?->block?->block ?? 'N/A' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Plot No</small>
                                    <div class="fw-bold">{{ $ledger->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">Booking Amount</small>
                                    <div class="fw-bold text-success">₹{{ number_format($bookingAmount, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Transactions --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-table text-success me-2"></i>
                                Ledger Transactions
                            </h5>
                            <small class="text-muted">
                                Complete debit/credit style payment history.
                            </small>
                        </div>

                        <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                            {{ $ledger->payments->count() }} Entries
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table id="ledgerTable" class="table table-hover align-middle nowrap w-100">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Receipt No</th>
                                    <th>Particular</th>
                                    <th>Payment Mode</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                    <th class="text-end">Balance</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $runningBalance = $totalCost;
                                @endphp

                                @forelse ($ledger->payments as $key => $payment)
                                    @php
                                        $credit = $payment->paid_amount ?? ($payment->booking_amount ?? 0);
                                        $runningBalance -= $credit;

                                        $status = strtolower(
                                            $payment->payment_status ?? ($payment->cheque_status ?? 'clear'),
                                        );

                                        $statusClass = match ($status) {
                                            'paid', 'cleared', 'clear' => 'bg-success',
                                            'hold', 'pending' => 'bg-warning text-dark',
                                            'bounce', 'bounced', 'cancelled', 'canceled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>{{ $payment->created_at?->format('d-m-Y') ?? 'N/A' }}</td>

                                        <td>{{ $payment->receipt_number ?? 'N/A' }}</td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ ucfirst(str_replace('_', ' ', $payment->transaction_category ?? 'Payment')) }}
                                            </div>
                                            <small class="text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $payment->plan_type ?? 'N/A')) }}
                                            </small>
                                        </td>

                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-2">
                                                {{ strtoupper($payment->payment_mode ?? 'N/A') }}
                                            </span>
                                        </td>

                                        <td class="text-end text-danger fw-bold">
                                            {{ $key == 0 ? '₹' . number_format($totalCost, 2) : '-' }}
                                        </td>

                                        <td class="text-end text-success fw-bold">
                                            ₹{{ number_format($credit, 2) }}
                                        </td>

                                        <td class="text-end fw-bold">
                                            ₹{{ number_format(max(0, $runningBalance), 2) }}
                                        </td>

                                        <td>
                                            <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">
                                                {{ strtoupper($payment->cheque_status ?? ($payment->payment_status ?? 'CLEAR')) }}
                                            </span>
                                        </td>

                                        <td>{{ $payment->remark ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                            <span class="text-muted">No payment transactions found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
                                <tr class="fw-bold table-light">
                                    <td colspan="5" class="text-end">Total</td>
                                    <td class="text-end text-danger">₹{{ number_format($totalCost, 2) }}</td>
                                    <td class="text-end text-success">₹{{ number_format($totalPaid, 2) }}</td>
                                    <td class="text-end text-danger">₹{{ number_format($totalDue, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
                    <h5 class="fw-bold mb-1">Search Customer Ledger</h5>
                    <p class="text-muted mb-0">
                        Please select filters to view customer ledger details.
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            if ($('#ledgerTable').length) {
                $('#ledgerTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    responsive: false,
                    scrollX: true,
                    language: {
                        emptyTable: 'No payment transactions found.'
                    }
                });
            }

            function loadBlocks(projectId, selectedBlockId = '') {
                $('#block_id').html('<option value="">Select Block</option>');
                $('#customer_id').html('<option value="">Select Customer</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');

                if (!projectId) {
                    return;
                }

                $.get('/ledger-project-blocks/' + projectId, function(response) {
                    $.each(response, function(index, item) {
                        let selected = String(selectedBlockId) === String(item.id) ? 'selected' :
                        '';
                        $('#block_id').append(
                            `<option value="${item.id}" ${selected}>${item.block}</option>`
                        );
                    });

                    if (selectedBlockId) {
                        loadCustomers(projectId, selectedBlockId, $('#customer_id').data('selected'));
                    }
                });
            }

            function loadCustomers(projectId, blockId, selectedCustomerId = '') {
                $('#customer_id').html('<option value="">Select Customer</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');

                if (!projectId || !blockId) {
                    return;
                }

                $.get('/ledger-block-customers/' + projectId + '/' + blockId, function(response) {
                    $.each(response, function(index, item) {
                        let selected = String(selectedCustomerId) === String(item.id) ? 'selected' :
                            '';
                        let name = item.primary_detail ? item.primary_detail.name : 'N/A';

                        $('#customer_id').append(
                            `<option value="${item.id}" ${selected}>${name}</option>`
                        );
                    });

                    if (selectedCustomerId) {
                        loadPlots(selectedCustomerId, $('#plot_id').data('selected'));
                    }
                });
            }

            function loadPlots(customerId, selectedPlotId = '') {
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');

                if (!customerId) {
                    return;
                }

                $.get('/ledger-customer-plots/' + customerId, function(response) {
                    $.each(response, function(index, item) {
                        if (item.plot_sale_detail && item.plot_sale_detail.plot_detail) {
                            let plotId = item.plot_sale_detail.plot_detail_id;
                            let plotNo = item.plot_sale_detail.plot_detail.plot_number;
                            let selected = String(selectedPlotId) === String(plotId) ? 'selected' :
                                '';

                            $('#plot_id').append(
                                `<option value="${plotId}" ${selected}>${plotNo}</option>`
                            );
                        }
                    });

                    if (selectedPlotId) {
                        loadBooking(selectedPlotId, customerId);
                    }
                });
            }

            function loadBooking(plotId, customerId) {
                if (plotId && customerId) {
                    $.get('/ledger-plot-booking/' + plotId + '/' + customerId, function(response) {
                        $('#booking_id').val(response.booking_code ?? '');
                    });
                }
            }

            $('#project_id').change(function() {
                loadBlocks($(this).val());
            });

            $('#block_id').change(function() {
                loadCustomers($('#project_id').val(), $(this).val());
            });

            $('#customer_id').change(function() {
                loadPlots($(this).val());
            });

            $('#plot_id').change(function() {
                loadBooking($(this).val(), $('#customer_id').val());
            });

            let selectedProjectId = $('#project_id').val();
            let selectedBlockId = $('#block_id').data('selected');

            if (selectedProjectId) {
                loadBlocks(selectedProjectId, selectedBlockId);
            }
        });
    </script>
@endpush
