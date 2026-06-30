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
                    <p class="text-muted mb-0 small">Select customer first, then choose booking or receipt group to download PDF.</p>
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
                        <label class="form-label fw-semibold">Customer <span class="text-danger">*</span></label>
                        <select name="customer_booking_id" id="customer_booking_id"
                            class="form-select @error('customer_booking_id') is-invalid @enderror">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ (old('customer_booking_id') ?? ($customer_booking_id ?? '')) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->customer_code }} / {{ $customer->primaryDetail?->name ?? $customer->customer_name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_booking_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Booking / Plot / Receipt</label>
                        <select name="receipt_group" id="receipt_group"
                            class="form-select @error('receipt_group') is-invalid @enderror"
                            data-selected="{{ old('receipt_group') ?? ($receipt_group ?? '') }}">
                            <option value="">All Receipts</option>
                        </select>
                        @error('receipt_group')
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
                                        <strong>{{ $receipt->group_booking_label ?? ($plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $plotSale?->project?->name ?? '-' }} /
                                            {{ $receipt->group_plot_label ?? (($plotSale?->block?->block ?? '-') . ' / ' . ($plotSale?->plotDetail?->plot_number ?? '-')) }}
                                            @if (($receipt->group_plot_count ?? 1) > 1)
                                                <span class="badge bg-success-subtle text-success border ms-1">
                                                    {{ $receipt->group_plot_count }} Plots
                                                </span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="fw-bold text-success">&#8377;{{ number_format((float) ($receipt->group_amount ?? $amount), 2) }}</td>
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

            function loadReceiptGroups(customerId) {
                const groupSelect = $('#receipt_group');
                const selectedGroup = groupSelect.data('selected') || '';

                groupSelect.html('<option value="">Loading receipts...</option>').prop('disabled', true);

                if (!customerId) {
                    groupSelect.html('<option value="">All Receipts</option>').prop('disabled', false);
                    return;
                }

                const url = "{{ route('receipt-reprint.groups', ':id') }}".replace(':id', customerId);

                $.get(url, function(res) {
                    groupSelect.html('<option value="">All Receipts</option>');

                    if (!res.length) {
                        groupSelect.append('<option value="">No receipt found</option>');
                        return;
                    }

                    $.each(res, function(index, group) {
                        const selected = String(selectedGroup) === String(group.id) ? 'selected' : '';
                        groupSelect.append(`
                            <option value="${group.id}" ${selected}>
                                ${group.text}
                            </option>
                        `);
                    });
                }).fail(function() {
                    groupSelect.html('<option value="">Unable to load receipts</option>');
                }).always(function() {
                    groupSelect.prop('disabled', false);
                });
            }

            $('#customer_booking_id').on('change', function() {
                $('#receipt_group').data('selected', '');
                loadReceiptGroups($(this).val());
            });

            $('#receiptSearchForm').on('submit', function(event) {
                if (!$('#customer_booking_id').val()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Details',
                        text: 'Please select customer first.'
                    });
                    return;
                }

                setSearchLoading(true);
            });

            if ($('#customer_booking_id').val()) {
                loadReceiptGroups($('#customer_booking_id').val());
            }
        });
    </script>
@endpush
