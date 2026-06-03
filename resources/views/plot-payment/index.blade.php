@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <h4 class="fw-bold mb-1">Edit Payment Details</h4>
                    <small class="text-muted">Manage and update customer payment entries</small>
                </div>

                <div class="col-md-8">
                    <form method="GET"
                        action="{{ route('edit-payment-details.index') }}"
                        id="paymentFilterForm"
                        class="row g-2 align-items-end">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small mb-1">
                                Select Payment
                            </label>

                            <select name="selected_payment"
                                id="paymentSelect"
                                class="form-select">

                                <option value="">-- Select Payment --</option>

                                @foreach ($payments as $payment)
                                    @php
                                        $booking = $payment->customerBooking;
                                        $plotSale = $payment->plotSaleDetail;
                                    @endphp

                                    <option value="{{ $payment->id }}"
                                        {{ request('selected_payment') == $payment->id ? 'selected' : '' }}>
                                        {{ $payment->receipt_number ?? 'N/A' }}
                                        -
                                        {{ $booking?->customer_code ?? 'N/A' }}
                                        -
                                        {{ $booking?->primaryDetail?->name ?? 'N/A' }}
                                        -
                                        Plot {{ $plotSale?->plotDetail?->plot_number ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('edit-payment-details.index') }}"
                                class="btn btn-outline-secondary w-100">
                                <i class="fa fa-refresh me-1"></i>
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($selectedPayment)
        @include('plot-payment.form')
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="paymentEditTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Receipt No</th>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Plot</th>
                            <th>Payment Type</th>
                            <th>Paid Amount</th>
                            <th>Pay Mode</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($payments as $key => $payment)
                            @php
                                $booking = $payment->customerBooking;
                                $plotSale = $payment->plotSaleDetail;
                            @endphp

                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $payment->receipt_number ?? 'N/A' }}</td>

                                <td>
                                    {{ $plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $booking?->customer_code ?? 'N/A' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $plotSale?->project?->name ?? '-' }}
                                    /
                                    {{ $plotSale?->block?->block ?? '-' }}
                                    /
                                    Plot {{ $plotSale?->plotDetail?->plot_number ?? '-' }}
                                </td>

                                <td>
                                    @if ($payment->transaction_category == 'booking_fee')
                                        Booking Amount
                                    @elseif ($payment->transaction_category == 'emi_payment')
                                        EMI Payment
                                    @elseif ($payment->transaction_category == 'one_time')
                                        One Time Payment
                                    @else
                                        Payment
                                    @endif
                                </td>

                                <td class="fw-bold text-success">
                                    ₹{{ number_format((float) ($payment->paid_amount ?? 0), 2) }}
                                </td>

                                <td>
                                    {{ strtoupper(str_replace('_', ' / ', $payment->payment_mode ?? '-')) }}
                                </td>

                                <td>
                                    @if ($payment->payment_status == 'cleared')
                                        <span class="badge bg-success">Cleared</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $payment->created_at?->format('d-M-Y') }}
                                </td>

                                <td>
                                    <a href="{{ route('edit-payment-details.index', ['selected_payment' => $payment->id]) }}"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    No payment records found.
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
    @include('plot-payment.script')

    <script>
        $(document).ready(function () {
            $('#paymentSelect').on('change', function () {
                if ($(this).val()) {
                    $('#paymentFilterForm').submit();
                }
            });

            if ($('#paymentEditTable tbody tr td').attr('colspan') === undefined) {
                $('#paymentEditTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }
        });
    </script>
@endpush