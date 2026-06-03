@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">

                    <div class="col-md-5">
                        <h3 class="fw-bold mb-1 text-dark">
                            EMI Generation
                        </h3>
                        <p class="text-muted mb-0 small">
                            Generate EMI schedule based on remaining due amount.
                        </p>
                    </div>

                    <div class="col-md-7">
                        <form method="GET" action="{{ route('generate-emi.index') }}"
                            class="d-flex justify-content-md-end gap-2 align-items-end flex-wrap">

                            <div style="width: 260px;">
                                <label class="mb-1 fw-semibold text-secondary small">
                                    Customer
                                </label>

                                <select name="customer_id" class="form-select">
                                    <option value="">All Customers</option>

                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_code }}
                                            -
                                            {{ $customer->primaryDetail?->name ?? $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('generate-emi.index') }}" class="btn btn-outline-secondary px-4">
                                Reset
                            </a>

                        </form>
                    </div>

                </div>
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

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0" id="emiTable">

                        <thead class="table-light">
                            <tr>
                                <th>Agent ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Project / Block / Plot</th>
                                <th>Total Cost</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th width="130">Duration</th>
                                <th width="150">EMI Amount</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($records as $row)
                                @php
                                    $booking = $row->customerBooking;
                                    $payments = $row->payments ?? collect();

                                    $totalCost = (float) ($row->total_plot_cost ?? 0);

                                    $paid = (float) $payments->where('booking_status', 'booked')->sum('paid_amount');

                                    $due = max(0, $totalCost - $paid);

                                    $latestPayment = $payments->sortByDesc('id')->first();

                                    $currentEmiMonths = $latestPayment?->emi_months;
                                @endphp

                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $booking?->associate?->associate_id ?? ($booking?->associate_code ?? '-') }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $booking?->customer_code ?? '-' }}
                                    </td>

                                    <td class="fw-medium">
                                        {{ $booking?->primaryDetail?->name ?? ($booking?->customer_name ?? '-') }}
                                    </td>

                                    <td>
                                        {{ $row->booking_code ?? ($booking?->booking_code ?? '-') }}
                                    </td>

                                    <td>
                                        <strong>{{ $row->project?->name ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Block:
                                            {{ $row->block?->block ?? '-' }}
                                            |
                                            Plot:
                                            {{ $row->plotDetail?->plot_number ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        ₹{{ number_format($totalCost, 2) }}
                                    </td>

                                    <td class="text-success fw-semibold">
                                        ₹{{ number_format($paid, 2) }}
                                    </td>

                                    <td class="fw-bold text-danger due-amount">
                                        {{ number_format($due, 2, '.', '') }}
                                    </td>

                                    <td>
                                        <input type="number" class="form-control emi-month" min="1"
                                            value="{{ $currentEmiMonths ?? '' }}" placeholder="Months"
                                            {{ $due <= 0 ? 'disabled' : '' }}>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control emi-amount bg-light" readonly
                                            placeholder="0.00">
                                    </td>

                                    <td>
                                        @if ($due > 0)
                                            <form method="POST" action="{{ route('generate-emi.store', $row->id) }}"
                                                class="generate-emi-form">

                                                @csrf

                                                <input type="hidden" name="emi_months" class="hidden-emi-month">

                                                <input type="hidden" name="emi_amount" class="hidden-emi-amount">

                                                <button type="submit" class="btn btn-sm btn-success px-3">
                                                    Generate
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">
                                                Paid
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        No EMI records found.
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
        $(document).ready(function() {

            function calculateEmi(row) {
                let dueAmount = parseFloat(
                    row.find('.due-amount').text().replace(/,/g, '')
                ) || 0;

                let months = parseInt(
                    row.find('.emi-month').val()
                ) || 0;

                let emiAmount = months > 0 ?
                    dueAmount / months :
                    0;

                row.find('.emi-amount').val(
                    emiAmount.toFixed(2)
                );

                row.find('.hidden-emi-month').val(months);
                row.find('.hidden-emi-amount').val(
                    emiAmount.toFixed(2)
                );
            }

            $('.emi-month').on('keyup change', function() {
                calculateEmi($(this).closest('tr'));
            });

            $('.emi-month').each(function() {
                calculateEmi($(this).closest('tr'));
            });

            $('.generate-emi-form').on('submit', function(e) {
                let row = $(this).closest('tr');
                let months = parseInt(row.find('.emi-month').val()) || 0;

                if (months <= 0) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid EMI Months',
                        text: 'Please enter valid EMI months.'
                    });

                    return false;
                }
            });

            if ($('#emiTable tbody tr td').attr('colspan') === undefined) {
                $('#emiTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }

        });
    </script>
@endpush
