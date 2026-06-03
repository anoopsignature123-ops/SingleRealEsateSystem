@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-1 text-dark">Find Receipt & Reprint</h3>
            <p class="text-muted mb-0 small">
                Search payment receipts by plot and customer.
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('receipt-reprint.search') }}">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Plot No <span class="text-danger">*</span>
                        </label>

                        <select name="plot_id"
                            id="plot_select"
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

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Customer <span class="text-danger">*</span>
                        </label>

                        <select name="customer_booking_id"
                            id="customer_booking_id"
                            class="form-select @error('customer_booking_id') is-invalid @enderror">

                            <option value="">Select Customer</option>
                        </select>

                        @error('customer_booking_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            Search
                        </button>
                    </div>
                </div>
            </form>

            @isset($receipts)
                <div class="table-responsive mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            Search Results
                        </h5>

                        <span class="badge bg-light text-dark border">
                            {{ count($receipts) }} Receipts
                        </span>
                    </div>

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer No</th>
                                <th>Name</th>
                                <th>Booking ID</th>
                                <th>Plot</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Payment Type</th>
                                <th>Receipt No</th>
                                <th>Date</th>
                                <th class="text-center">Download</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($receipts as $key => $receipt)
                                @php
                                    $booking = $receipt->customerBooking;
                                    $plotSale = $receipt->plotSaleDetail;
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $booking?->customer_code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $plotSale?->project?->name ?? '-' }}
                                        /
                                        {{ $plotSale?->block?->block ?? '-' }}
                                        /
                                        {{ $plotSale?->plotDetail?->plot_number ?? '-' }}
                                    </td>

                                    <td class="fw-bold text-success">
                                        ₹{{ number_format((float) ($receipt->paid_amount ?? 0), 2) }}
                                    </td>

                                    <td>
                                        {{ strtoupper(str_replace('_', ' / ', $receipt->payment_mode ?? 'N/A')) }}
                                    </td>

                                    <td>
                                        @if ($receipt->transaction_category == 'booking_fee')
                                            Booking Amount
                                        @elseif ($receipt->transaction_category == 'emi_payment')
                                            EMI Payment
                                        @elseif ($receipt->transaction_category == 'one_time')
                                            One Time Payment
                                        @else
                                            Payment
                                        @endif
                                    </td>

                                    <td>
                                        {{ $receipt->receipt_number ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $receipt->created_at ? $receipt->created_at->format('d-M-Y') : 'N/A' }}
                                    </td>

                                    <td class="text-center">
                                        <a target="_blank"
                                            href="{{ route('receipt-reprint.download', $receipt->id) }}"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endisset

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#plot_select').on('change', function () {
        let plotId = $(this).val();
        let oldCustomerId = "{{ old('customer_booking_id') ?? ($customer_booking_id ?? '') }}";

        $('#customer_booking_id').html('<option value="">Select Customer</option>');

        if (!plotId) return;

        let url = "{{ route('receipt-reprint.customers', ':id') }}".replace(':id', plotId);

        $.get(url, function (res) {
            $.each(res, function (index, customer) {
                let selected = oldCustomerId == customer.id ? 'selected' : '';

                $('#customer_booking_id').append(`
                    <option value="${customer.id}" ${selected}>
                        ${customer.text}
                    </option>
                `);
            });
        });
    });

    if ($('#plot_select').val()) {
        $('#plot_select').trigger('change');
    }
});
</script>
@endpush