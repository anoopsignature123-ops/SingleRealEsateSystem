@extends('layouts.app')

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
                                        {{ $customer->primaryDetail?->name ? ' | '.$customer->primaryDetail->name : '' }}
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
                                        {{ $row->group_projects ?: ($row->project?->name ?? '-') }} /
                                        Block {{ $row->group_blocks ?: ($row->block?->block ?? '-') }} /
                                        Plot {{ $row->group_plot_numbers ?: ($row->plotDetail?->plot_number ?? '-') }}
                                    </small>
                                    @if ($plotCount > 1)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                            {{ $plotCount }} Plots
                                        </span>
                                    @endif
                                </td>
                                <td class="fw-bold">&#8377;{{ number_format($totalCost, 2) }}</td>
                                <td class="text-success fw-bold">&#8377;{{ number_format($paid, 2) }}</td>
                                <td>
                                    <span class="text-danger fw-bold">&#8377;<span class="due-amount">{{ number_format($due, 2, '.', '') }}</span></span>
                                </td>
                                <td>
                                    <input type="number" class="form-control emi-month" min="1"
                                        value="{{ $currentEmiMonths ?? '' }}" placeholder="Months"
                                        {{ ! $canGenerateEmi ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <input type="text" class="form-control emi-amount bg-white fw-bold text-success" readonly
                                        placeholder="0.00">
                                </td>
                                <td class="text-center">
                                    @if ($canGenerateEmi)
                                        <form method="POST" action="{{ route('generate-emi.store', $row->id) }}"
                                            class="generate-emi-form">
                                            @csrf
                                            <input type="hidden" name="emi_months" class="hidden-emi-month">
                                            <input type="hidden" name="emi_amount" class="hidden-emi-amount">

                                            <button type="submit" class="btn btn-sm btn-success generate-emi-btn">
                                                <span class="btn-label">
                                                    <i class="bi {{ $isEmiGenerated ? 'bi-arrow-repeat' : 'bi-calendar-plus' }} me-1"></i>
                                                    Generate EMI
                                                </span>
                                                <span class="btn-loader d-none">
                                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                    Saving
                                                </span>
                                            </button>
                                        </form>
                                    @elseif ($due <= 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            Payment Missing
                                        </span>
                                    @endif
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function calculateEmi(row) {
                const dueAmount = parseFloat(row.find('.due-amount').text().replace(/,/g, '')) || 0;
                const months = parseInt(row.find('.emi-month').val()) || 0;
                const emiAmount = months > 0 ? dueAmount / months : 0;

                row.find('.emi-amount').val(emiAmount.toFixed(2));
                row.find('.hidden-emi-month').val(months);
                row.find('.hidden-emi-amount').val(emiAmount.toFixed(2));
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
