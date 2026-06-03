@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">Update EMI Date</h3>
                        <p class="text-muted mb-0 small">
                            Update EMI date for single or multiple EMI records.
                        </p>
                    </div>

                    <button type="button" id="bulk_update_btn" class="btn btn-success d-none" data-bs-toggle="modal"
                        data-bs-target="#bulkDateModal">

                        <i class="fas fa-calendar-alt me-1"></i>
                        Update Selected EMI Date
                    </button>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="emiDateTable">

                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">
                                    <input type="checkbox" id="select_all" class="form-check-input">
                                </th>
                                <th>Agent ID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Booking ID</th>
                                <th>Project / Block / Plot</th>
                                <th>Monthly EMI</th>
                                <th>Remaining EMI</th>
                                <th>Last EMI Date</th>
                                <th>Current EMI Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($payments as $payment)
                                @php
                                    $booking = $payment->customerBooking;
                                    $plotSale = $payment->plotSaleDetail;
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input payment_checkbox"
                                            value="{{ $payment->id }}">
                                    </td>

                                    <td>
                                        {{ $booking?->associate_code ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $booking?->customer_code ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $booking?->primaryDetail?->name ?? ($booking?->customer_name ?? '-') }}
                                    </td>

                                    <td>
                                        {{ $plotSale?->booking_code ?? ($booking?->booking_code ?? '-') }}
                                    </td>

                                    <td>
                                        <strong>{{ $plotSale?->project?->name ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Block:
                                            {{ $plotSale?->block?->block ?? '-' }}
                                            |
                                            Plot:
                                            {{ $plotSale?->plotDetail?->plot_number ?? '-' }}
                                        </small>
                                    </td>

                                    <td class="fw-semibold text-success">
                                        ₹{{ number_format((float) ($payment->after_booking_payable_amount ?? ($payment->paid_amount ?? 0)), 2) }}
                                    </td>

                                    <td>
                                        {{ $payment->emi_months ?? 0 }} Months
                                    </td>

                                    <td>
                                        {{ $payment->created_at ? $payment->created_at->format('d-m-Y') : '-' }}
                                    </td>

                                    <td>
                                        @if ($payment->emi_date)
                                            <span class="badge bg-light text-dark border">
                                                {{ \Carbon\Carbon::parse($payment->emi_date)->format('d-m-Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
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

    {{-- Modal --}}
    <div class="modal fade" id="bulkDateModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">

                <form method="POST" action="{{ route('update-emi-date.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            Update EMI Date
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="payment_ids" id="payment_ids">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Select New EMI Date
                            </label>

                            <input type="date" name="emi_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="submit" class="btn btn-success">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@include('payment.update-emi-date.script')
