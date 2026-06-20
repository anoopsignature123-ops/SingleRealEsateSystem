@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4 receipt-reprint-page">
        <div class="receipt-reprint-hero mb-4">
            <div class="d-flex align-items-center gap-3">
                <span class="receipt-reprint-hero-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </span>
                <div>
                    <span class="text-success fw-bold text-uppercase small">Receipt Center</span>
                    <h3 class="fw-bold mb-1 text-dark">Find Receipt & Reprint</h3>
                    <p class="text-muted mb-0 small">Search payment receipts by plot and customer, then download PDF.</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please check:</strong> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="receipt-search-card mb-4">
            <form method="POST" action="{{ route('receipt-reprint.search') }}" id="receiptSearchForm">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Plot No <span class="text-danger">*</span></label>
                        <select name="plot_id" id="plot_select"
                            class="form-select @error('plot_id') is-invalid @enderror">
                            <option value="">Select Plot</option>
                            @foreach ($plots as $plot)
                                <option value="{{ $plot->id }}"
                                    {{ (old('plot_id') ?? ($plot_id ?? '')) == $plot->id ? 'selected' : '' }}>
                                    {{ $plot->plot_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('plot_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_booking_id" id="customer_booking_id"
                            class="form-select @error('customer_booking_id') is-invalid @enderror"
                            data-selected="{{ old('customer_booking_id') ?? ($customer_booking_id ?? '') }}">
                            <option value="">Select Customer</option>
                        </select>
                        @error('customer_booking_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100" id="receiptSearchBtn">
                            <span class="btn-label">
                                <i class="bi bi-search me-1"></i> Search
                            </span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                Searching...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @isset($receipts)
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="receipt-stat-card">
                        <small>Total Receipts</small>
                        <strong>{{ $summary['count'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="receipt-stat-card success">
                        <small>Total Amount</small>
                        <strong>&#8377;{{ number_format((float) ($summary['amount'] ?? 0), 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="receipt-stat-card info">
                        <small>Latest Receipt</small>
                        <strong>
                            {{ !empty($summary['latest']) ? \Carbon\Carbon::parse($summary['latest'])->format('d-M-Y') : '-' }}
                        </strong>
                    </div>
                </div>
            </div>

            <div class="receipt-result-card">
                <div class="receipt-result-head">
                    <div>
                        <h5 class="fw-bold mb-1">Search Results</h5>
                        <small class="text-muted">Download duplicate payment receipts from here.</small>
                    </div>
                    <span class="badge bg-light text-dark border">
                        {{ count($receipts) }} Receipts
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 receipt-result-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Receipt</th>
                                <th>Customer</th>
                                <th>Booking / Plot</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($receipts as $key => $receipt)
                                @php
                                    $booking = $receipt->customerBooking;
                                    $plotSale = $receipt->plotSaleDetail;
                                    $amount = (float) ($receipt->paid_amount ?? $receipt->booking_amount ?? 0);
                                    $paymentType = match ($receipt->transaction_category) {
                                        'booking_fee' => 'Booking Amount',
                                        'emi_payment' => 'EMI Payment',
                                        'one_time' => 'One Time Payment',
                                        default => 'Payment',
                                    };
                                    $statusClass = ($receipt->booking_status ?? '') === 'booked' ? 'success' : 'warning';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $receipt->receipt_number ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $receipt->created_at ? $receipt->created_at->format('d-M-Y') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $booking?->customer_code ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $plotSale?->project?->name ?? '-' }} /
                                            {{ $plotSale?->block?->block ?? '-' }} /
                                            {{ $plotSale?->plotDetail?->plot_number ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="fw-bold text-success">&#8377;{{ number_format($amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border">
                                            {{ strtoupper(str_replace('_', ' / ', $receipt->payment_mode ?? 'N/A')) }}
                                        </span>
                                    </td>
                                    <td>{{ $paymentType }}</td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($receipt->booking_status ?? 'N/A') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ ucfirst($receipt->payment_status ?? 'N/A') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a target="_blank" href="{{ route('receipt-reprint.download', $receipt->id) }}"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        No receipt records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function setSearchLoading(isLoading) {
                const button = $('#receiptSearchBtn');
                button.prop('disabled', isLoading);
                button.find('.btn-label').toggleClass('d-none', isLoading);
                button.find('.btn-loader').toggleClass('d-none', !isLoading);
            }

            function loadCustomers(plotId) {
                const customerSelect = $('#customer_booking_id');
                const selectedCustomerId = customerSelect.data('selected') || '';

                customerSelect.html('<option value="">Loading customers...</option>').prop('disabled', true);

                if (!plotId) {
                    customerSelect.html('<option value="">Select Customer</option>').prop('disabled', false);
                    return;
                }

                const url = "{{ route('receipt-reprint.customers', ':id') }}".replace(':id', plotId);

                $.get(url, function(res) {
                    customerSelect.html('<option value="">Select Customer</option>');

                    if (!res.length) {
                        customerSelect.append('<option value="">No customer found</option>');
                        return;
                    }

                    $.each(res, function(index, customer) {
                        const selected = String(selectedCustomerId) === String(customer.id) ? 'selected' : '';
                        customerSelect.append(`
                            <option value="${customer.id}" ${selected}>
                                ${customer.text}
                            </option>
                        `);
                    });
                }).fail(function() {
                    customerSelect.html('<option value="">Unable to load customers</option>');
                }).always(function() {
                    customerSelect.prop('disabled', false);
                });
            }

            $('#plot_select').on('change', function() {
                $('#customer_booking_id').data('selected', '');
                loadCustomers($(this).val());
            });

            $('#receiptSearchForm').on('submit', function(event) {
                if (!$('#plot_select').val() || !$('#customer_booking_id').val()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Details',
                        text: 'Please select plot and customer first.'
                    });
                    return;
                }

                setSearchLoading(true);
            });

            if ($('#plot_select').val()) {
                loadCustomers($('#plot_select').val());
            }
        });
    </script>
@endpush
