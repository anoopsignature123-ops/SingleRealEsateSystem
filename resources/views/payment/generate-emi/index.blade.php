@extends('layouts.app')

@push('title')
    Generate EMI
@endpush
@section('content')
    @php
        $totalDue = 0;
        $emiReadyCount = 0;
        $alreadyGeneratedCount = 0;

        foreach ($records as $record) {
            $due = (float) ($record->group_due ?? 0);

            $totalDue += $due;

            if ($record->group_can_generate ?? false) {
                $emiReadyCount++;
            }

            if ($record->group_is_emi_generated ?? false) {
                $alreadyGeneratedCount++;
            }
        }
    @endphp

    <div class="container-fluid mt-4 transaction-page generate-emi-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-calendar2-plus"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Payment Section</span>
                        <h3 class="fw-bold mb-1 text-dark">Generate EMI</h3>
                        <p class="text-muted mb-0 small">Generate monthly EMI amount for EMI plan bookings only.</p>
                    </div>
                </div>

                <span class="transaction-count">{{ $records->count() }} Records</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-file-earmark-spreadsheet"></i></span>
                    <div>
                        <small>Total Records</small>
                        <strong>{{ $records->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-cash-coin"></i></span>
                    <div>
                        <small>Total Due</small>
                        <strong>&#8377;{{ number_format($totalDue, 2) }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-check2-circle"></i></span>
                    <div>
                        <small>EMI Generated</small>
                        <strong>{{ $alreadyGeneratedCount }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="transaction-card mb-4">
            <div class="transaction-card-body">
                <div class="transaction-section-title">
                    <div class="d-flex align-items-center gap-3">
                        <span class="transaction-section-title-icon">
                            <i class="bi bi-funnel"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-1">Find Booking</h5>
                            <small class="text-muted">Only customers with EMI plan bookings are listed here.</small>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('generate-emi.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-9 col-md-8">
                            <label class="form-label fw-semibold">Customer</label>
                            <select name="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }}
                                        {{ $customer->primaryDetail?->name ? ' | ' . $customer->primaryDetail->name : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-search me-1"></i>
                                    Search
                                </button>
                                <a href="{{ route('generate-emi.index') }}" class="btn btn-outline-secondary flex-fill">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="transaction-card transaction-history-card generate-emi-table-card mb-4">
            <div class="transaction-history-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-section-title-icon">
                        <i class="bi bi-calculator"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-1">EMI Eligible Bookings</h5>
                        <small class="text-muted">{{ $emiReadyCount }} EMI plan bookings have pending due amount.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $records->count() }} Records</span>
            </div>

            <div class="transaction-table-wrap">
                @php
                    $generateEmiOverviews = [];
                @endphp
                <table class="table transaction-table align-middle mb-0" id="emiTable">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Customer</th>
                            <th>Booking / Plot</th>
                            <th>Total Cost</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Months</th>
                            <th>Monthly EMI</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $row)
                            @php
                                $booking = $row->customerBooking;
                                $totalCost = (float) ($row->group_total_cost ?? 0);
                                $paid = (float) ($row->group_paid ?? 0);
                                $due = (float) ($row->group_due ?? 0);
                                $currentEmiMonths = $row->group_current_emi_months;
                                $isEmiGenerated = (bool) ($row->group_is_emi_generated ?? false);
                                $canGenerateEmi = (bool) ($row->group_can_generate ?? false);
                                $plotCount = (int) ($row->group_plot_count ?? 1);
                                $overview = [
                                    'record_id' => $row->id,
                                    'booking' => $row->booking_code ?? ($booking?->booking_code ?? '-'),
                                    'customer' => $booking?->primaryDetail?->name ?? ($booking?->customer_name ?? '-'),
                                    'customer_code' => $booking?->customer_code ?? '-',
                                    'plots' => $row->group_plot_numbers ?: $row->plotDetail?->plot_number ?? '-',
                                    'plot_count' => $plotCount,
                                    'total_cost' => number_format($totalCost, 2),
                                    'paid' => number_format($paid, 2),
                                    'hold' => number_format((float) ($row->group_hold ?? 0), 2),
                                    'due' => number_format($due, 2),
                                    'monthly_emi' => number_format((float) ($row->group_monthly_emi ?? 0), 2),
                                    'total_installments' => (int) ($row->group_emi_total_installments ?? 0),
                                    'paid_installments' => (int) ($row->group_emi_paid_installments ?? 0),
                                    'hold_installments' => (int) ($row->group_emi_hold_installments ?? 0),
                                    'remaining_installments' => (int) ($row->group_emi_remaining_installments ?? 0),
                                    'progress_percent' => (int) ($row->group_emi_progress_percent ?? 0),
                                    'is_emi_generated' => $isEmiGenerated,
                                    'plot_breakdown' => $row->group_plot_breakdown ?? collect(),
                                    'emi_schedule' => $row->group_emi_schedule ?? collect(),
                                ];
                                $generateEmiOverviews[$row->id] = $overview;
                            @endphp

                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $booking?->associate?->associate_id ?? ($booking?->associate_code ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        {{ $booking?->primaryDetail?->name ?? ($booking?->customer_name ?? '-') }}
                                    </div>
                                    <small class="text-muted">{{ $booking?->customer_code ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        {{ $row->booking_code ?? ($booking?->booking_code ?? '-') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $row->group_projects ?: $row->project?->name ?? '-' }} /
                                        Block {{ $row->group_blocks ?: $row->block?->block ?? '-' }} /
                                        Plot {{ $row->group_plot_numbers ?: $row->plotDetail?->plot_number ?? '-' }}
                                    </small>
                                    @if ($plotCount > 1)
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                            {{ $plotCount }} Plots
                                        </span>
                                    @endif
                                </td>
                                <td class="fw-bold">&#8377;{{ number_format($totalCost, 2) }}</td>
                                <td class="text-success fw-bold">&#8377;{{ number_format($paid, 2) }}</td>
                                <td>
                                    <span class="text-danger fw-bold">&#8377;<span
                                            class="due-amount">{{ number_format($due, 2, '.', '') }}</span></span>
                                </td>
                                <td>
                                    <input type="number" class="form-control emi-month" min="1"
                                        value="{{ $currentEmiMonths ?? '' }}" placeholder="Months"
                                        {{ !$canGenerateEmi ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <input type="text" class="form-control emi-amount bg-white fw-bold text-success"
                                        readonly placeholder="0.00">
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                        <button type="button" class="btn btn-sm btn-outline-success view-emi-btn"
                                            data-record-id="{{ $row->id }}">
                                            <i class="bi bi-eye me-1"></i> View EMI
                                        </button>

                                        @if ($canGenerateEmi)
                                            <form method="POST" action="{{ route('generate-emi.store', $row->id) }}"
                                                class="generate-emi-form m-0">
                                                @csrf

                                                <input type="hidden" name="emi_months" class="hidden-emi-month">
                                                <input type="hidden" name="emi_amount" class="hidden-emi-amount">

                                                <button type="submit" class="btn btn-sm btn-success generate-emi-btn">
                                                    <span class="btn-label">
                                                        <i
                                                            class="bi {{ $isEmiGenerated ? 'bi-arrow-repeat' : 'bi-calendar-plus' }} me-1"></i>
                                                        Generate EMI
                                                    </span>

                                                    <span class="btn-loader d-none">
                                                        <span class="spinner-border spinner-border-sm me-1"
                                                            role="status"></span>
                                                        Saving...
                                                    </span>
                                                </button>
                                            </form>
                                        @elseif ($due <= 0)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">
                                                Paid
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                Payment Missing
                                            </span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-muted"></i>
                                    No EMI records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="viewEmiModal" tabindex="-1" aria-labelledby="viewEmiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <div>
                            <span class="text-success fw-bold text-uppercase small">EMI View</span>
                            <h5 class="modal-title fw-bold mb-0" id="viewEmiModalLabel">Booking EMI Details</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-lg-4 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted fw-semibold text-uppercase">Booking</small>
                                    <h6 class="fw-bold mb-1" id="modal_booking_code">-</h6>
                                    <small class="text-muted" id="modal_customer_detail">-</small>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-muted fw-semibold text-uppercase">Plots</small>
                                    <h6 class="fw-bold mb-1" id="modal_plot_count">0 Plot</h6>
                                    <small class="text-muted" id="modal_plot_numbers">-</small>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="border rounded-3 p-3 h-100 bg-success-subtle">
                                    <small class="text-success fw-semibold text-uppercase">Current Selection</small>
                                    <h6 class="fw-bold mb-1" id="modal_selected_months">0 Months</h6>
                                    <small class="text-success" id="modal_selected_emi">&#8377;0.00 Monthly EMI</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-3 col-6">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-muted fw-semibold text-uppercase">Total Cost</small>
                                    <h5 class="fw-bold mb-0">&#8377;<span id="modal_total_cost">0.00</span></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded-3 p-3 bg-success-subtle">
                                    <small class="text-success fw-semibold text-uppercase">Paid</small>
                                    <h5 class="fw-bold text-success mb-0">&#8377;<span id="modal_paid">0.00</span></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded-3 p-3 bg-warning-subtle">
                                    <small class="text-warning fw-semibold text-uppercase">Hold</small>
                                    <h5 class="fw-bold text-warning mb-0">&#8377;<span id="modal_hold">0.00</span></h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="border rounded-3 p-3 bg-danger-subtle">
                                    <small class="text-danger fw-semibold text-uppercase">Due</small>
                                    <h5 class="fw-bold text-danger mb-0">&#8377;<span id="modal_due">0.00</span></h5>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">EMI Progress</h6>
                                    <small class="text-muted">Receipt-wise paid, hold and remaining installment
                                        status.</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border" id="modal_total_emi_badge">0
                                    EMI</span>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-3 col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-muted fw-semibold">Total EMI</small>
                                        <h5 class="fw-bold mb-0" id="modal_total_emi">0</h5>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-success fw-semibold">Paid</small>
                                        <h5 class="fw-bold text-success mb-0" id="modal_paid_emi">0</h5>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-warning fw-semibold">Hold</small>
                                        <h5 class="fw-bold text-warning mb-0" id="modal_hold_emi">0</h5>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-danger fw-semibold">Remaining</small>
                                        <h5 class="fw-bold text-danger mb-0" id="modal_remaining_emi">0</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="progress" style="height: 12px;" role="progressbar" aria-label="EMI progress"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-success" id="modal_progress_bar" style="width: 0%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 small">
                                <span class="text-muted fw-semibold">Paid progress</span>
                                <strong class="text-success" id="modal_progress_text">0% Paid</strong>
                            </div>
                        </div>

                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">EMI Schedule</h6>
                                    <small class="text-muted">Installment-wise due date, payment status and receipt
                                        details.</small>
                                </div>
                                <span class="badge bg-light text-dark border" id="modal_schedule_count">0 Rows</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                            <th>Paid Date</th>
                                            <th>Mode</th>
                                            <th>Plots</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal_emi_schedule">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">No EMI schedule found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3 p-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">Plot Breakdown</h6>
                                    <small class="text-muted">Plot-wise payable amount and EMI progress.</small>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Plot</th>
                                        <th>Area</th>
                                        <th>Total Cost</th>
                                        <th>Paid / Hold</th>
                                        <th>Monthly EMI</th>
                                        <th>EMI Status</th>
                                        <th class="text-end">Due</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_plot_breakdown">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No plot details found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const generateEmiOverviews = @json($generateEmiOverviews);

            function parseAmount(value) {
                return parseFloat(String(value || '0').replace(/,/g, '')) || 0;
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function numberFormat(value) {
                return Number(value || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function calculateEmi(row) {
                const dueAmount = parseFloat(row.find('.due-amount').text().replace(/,/g, '')) || 0;
                const months = parseInt(row.find('.emi-month').val()) || 0;
                const emiAmount = months > 0 ? dueAmount / months : 0;

                row.find('.emi-amount').val(emiAmount.toFixed(2));
                row.find('.hidden-emi-month').val(months);
                row.find('.hidden-emi-amount').val(emiAmount.toFixed(2));
            }

            function scheduleStatusBadge(status) {
                status = String(status || '').toLowerCase();

                if (status === 'paid') {
                    return 'badge bg-success-subtle text-success border border-success-subtle';
                }

                if (status === 'hold') {
                    return 'badge bg-warning-subtle text-warning border border-warning-subtle';
                }

                return 'badge bg-danger-subtle text-danger border border-danger-subtle';
            }

            function renderViewEmiModal(button) {
                const row = button.closest('tr');
                const recordId = String(button.data('record-id') || '');
                const overview = generateEmiOverviews[recordId] || generateEmiOverviews[parseInt(recordId, 10)] ||
                    {};

                const selectedMonths = parseInt(row.find('.emi-month').val()) || 0;
                const selectedEmi = parseAmount(row.find('.emi-amount').val());
                const paidInstallments = parseInt(overview.paid_installments || 0, 10);
                const holdInstallments = parseInt(overview.hold_installments || 0, 10);
                const remainingInstallments = selectedMonths > 0 ?
                    selectedMonths :
                    parseInt(overview.remaining_installments || 0, 10);
                const totalInstallments = paidInstallments + holdInstallments + remainingInstallments;
                const progressPercent = totalInstallments > 0 ?
                    Math.min(100, Math.round((paidInstallments / totalInstallments) * 100)) :
                    0;
                const plotCount = parseInt(overview.plot_count || 0, 10);
                const plots = Array.isArray(overview.plot_breakdown) ? overview.plot_breakdown : [];
                const schedule = Array.isArray(overview.emi_schedule) ? overview.emi_schedule : [];

                $('#modal_booking_code').text(overview.booking || '-');
                $('#modal_customer_detail').text((overview.customer_code || '-') + ' | ' + (overview.customer ||
                    '-'));
                $('#modal_plot_count').text(plotCount + ' Plot' + (plotCount === 1 ? '' : 's'));
                $('#modal_plot_numbers').text(overview.plots || '-');
                $('#modal_selected_months').text(selectedMonths + (selectedMonths === 1 ? ' Month' : ' Months'));
                $('#modal_selected_emi').html('&#8377;' + numberFormat(selectedEmi) + ' Monthly EMI');
                $('#modal_total_cost').text(overview.total_cost || '0.00');
                $('#modal_paid').text(overview.paid || '0.00');
                $('#modal_hold').text(overview.hold || '0.00');
                $('#modal_due').text(overview.due || '0.00');
                $('#modal_total_emi_badge').text(totalInstallments + (totalInstallments === 1 ? ' EMI' : ' EMIs'));
                $('#modal_total_emi').text(totalInstallments);
                $('#modal_paid_emi').text(paidInstallments);
                $('#modal_hold_emi').text(holdInstallments);
                $('#modal_remaining_emi').text(remainingInstallments);
                $('#modal_progress_bar').css('width', progressPercent + '%').attr('aria-valuenow', progressPercent);
                $('#modal_progress_text').text(progressPercent + '% Paid');

                let scheduleRows = '';
                schedule.forEach(function(emi) {
                    scheduleRows += `<tr>
                        <td class="fw-bold">${escapeHtml(emi.installment_no || '-')}</td>
                        <td class="text-nowrap">${escapeHtml(emi.due_date || '-')}</td>
                        <td class="text-nowrap fw-semibold">&#8377;${escapeHtml(emi.amount || '0.00')}</td>
                        <td>
                            <span class="${scheduleStatusBadge(emi.raw_status || emi.status)}">
                                ${escapeHtml(emi.status || '-')}
                            </span>
                        </td>
                        <td class="text-nowrap">${escapeHtml(emi.receipt_no || '-')}</td>
                        <td class="text-nowrap">${escapeHtml(emi.paid_date || '-')}</td>
                        <td>${escapeHtml(emi.mode || '-')}</td>
                        <td>${escapeHtml(emi.plots || '-')}</td>
                    </tr>`;
                });

                if (!scheduleRows) {
                    scheduleRows = `<tr>
                        <td colspan="8" class="text-center text-muted py-4">No EMI schedule found.</td>
                    </tr>`;
                }

                $('#modal_schedule_count').text(schedule.length + (schedule.length === 1 ? ' Row' : ' Rows'));
                $('#modal_emi_schedule').html(scheduleRows);

                let rows = '';

                plots.forEach(function(plot) {
                    const dueAmount = parseAmount(plot.due);
                    const proposedPlotEmi = selectedMonths > 0 ? dueAmount / selectedMonths : parseAmount(
                        plot.monthly_emi);
                    const plotPaid = paidInstallments;
                    const plotHold = holdInstallments;
                    const plotRemaining = remainingInstallments;
                    const plotTotal = totalInstallments;
                    const plotProgress = progressPercent;

                    rows += `<tr>
                        <td>
                            <strong>${escapeHtml(plot.plot || '-')}</strong>
                            <span class="d-block small text-muted">${escapeHtml(plot.project || '-')} / Block ${escapeHtml(plot.block || '-')}</span>
                        </td>
                        <td class="text-nowrap">${escapeHtml(plot.area || '0.00')} Sq.Ft.</td>
                        <td class="text-nowrap">&#8377;${escapeHtml(plot.total_cost || '0.00')}</td>
                        <td class="text-nowrap">
                            <span class="text-success fw-semibold">&#8377;${escapeHtml(plot.paid || '0.00')}</span>
                            <span class="d-block small text-warning">Hold: &#8377;${escapeHtml(plot.hold || '0.00')}</span>
                        </td>
                        <td class="text-nowrap">&#8377;${numberFormat(proposedPlotEmi)}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                <span class="badge bg-success-subtle text-success border">${plotPaid} Paid</span>
                                <span class="badge bg-warning-subtle text-warning border">${plotHold} Hold</span>
                                <span class="badge bg-danger-subtle text-danger border">${plotRemaining} Left</span>
                            </div>
                            <div class="progress" style="height: 7px;" role="progressbar" aria-valuenow="${plotProgress}"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-success" style="width: ${plotProgress}%"></div>
                            </div>
                            <small class="text-muted">${plotPaid}/${plotTotal} group EMI paid</small>
                        </td>
                        <td class="text-end text-nowrap fw-bold">&#8377;${escapeHtml(plot.due || '0.00')}</td>
                    </tr>`;
                });

                if (!rows) {
                    rows = `<tr>
                        <td colspan="7" class="text-center text-muted py-4">No plot details found.</td>
                    </tr>`;
                }

                $('#modal_plot_breakdown').html(rows);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('viewEmiModal')).show();
            }

            $('.emi-month').on('keyup change', function() {
                calculateEmi($(this).closest('tr'));
            });

            $('.emi-month').each(function() {
                calculateEmi($(this).closest('tr'));
            });

            $('.generate-emi-form').on('submit', function(e) {
                const form = $(this);
                const row = form.closest('tr');
                const months = parseInt(row.find('.emi-month').val()) || 0;

                if (months <= 0) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid EMI Months',
                        text: 'Please enter valid EMI months.'
                    });

                    return false;
                }

                const button = form.find('.generate-emi-btn');
                button.prop('disabled', true);
                button.find('.btn-label').addClass('d-none');
                button.find('.btn-loader').removeClass('d-none');
            });

            $(document).on('click', '.view-emi-btn', function() {
                const row = $(this).closest('tr');
                calculateEmi(row);
                renderViewEmiModal($(this));
            });

            const hasRecords = {{ $records->count() > 0 ? 'true' : 'false' }};

            if (hasRecords) {
                $('#emiTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    ordering: true,
                    columnDefs: [{
                        orderable: false,
                        targets: [6, 7, 8]
                    }],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search EMI records...'
                    }
                });
            }
        });
    </script>
@endpush
