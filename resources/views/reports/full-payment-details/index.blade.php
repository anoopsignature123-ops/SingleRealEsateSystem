@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-cash-coin me-2"></i>
                    Full Payment Details Report
                </h3>
                <small class="text-muted">
                    Search and export payment reports
                </small>
            </div>

            <span class="badge badge-report">
                Total: {{ count($payments) }}
            </span>
        </div>

        {{-- Filter Card --}}
        <div class="card report-card mb-4">

            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Report
                </h5>
            </div>

            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label>Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->customer_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control"
                                placeholder="Customer Name" value="{{ request('customer_name') }}" readonly>
                        </div>

                        <div class="col-md-2">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-select">
                                <option value="">Select</option>

                                <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>
                                    Cash
                                </option>

                                <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>
                                    Cheque
                                </option>

                                <option value="dd" {{ request('payment_mode') == 'dd' ? 'selected' : '' }}>
                                    DD
                                </option>

                                <option value="neft_rtgs" {{ request('payment_mode') == 'neft_rtgs' ? 'selected' : '' }}>
                                    NEFT / RTGS
                                </option>

                                <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>
                                    Card
                                </option>
                            </select>
                        </div>

                        <div class="col-md-5 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('full-payment-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('full-payment-details-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Export
                            </a>

                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card report-card">

            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Payment Records
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table id="paymentTable" class="table table-hover align-middle nowrap w-100">

                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking Id</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>PLC Amt.</th>
                                <th>Other Charges</th>
                                <th>Discount</th>
                                <th>Payable Amount</th>
                                <th>Paid Amount</th>
                                <th>Pay Mode</th>
                                <th>Pay Type</th>
                                <th>Status</th>
                                <th>Chq Date</th>
                                <th>Pay Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>

                        @php
                            $totalPayable = 0;
                            $totalPaid = 0;
                        @endphp

                        <tbody>

                            @foreach ($payments as $key => $payment)
                                @php
                                    $plotSale = $payment->plotSaleDetail;
                                    $paidAmount = $payment->booking_amount ?? 0;
                                    $payableAmount = $payment->net_payable_amount ?? 0;

                                    $totalPayable += $payableAmount;
                                    $totalPaid += $paidAmount;
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $payment->customerBooking?->booking_code ?? 'N/A' }}</td>
                                    <td>{{ $payment->customerBooking?->primaryDetail?->name ?? 'N/A' }}</td>
                                    <td>{{ $plotSale?->project?->name ?? 'N/A' }}</td>
                                    <td>{{ $plotSale?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($plotSale?->plc_amount ?? 0, 2) }}</td>
                                    <td>₹{{ number_format($plotSale?->other_charges ?? 0, 2) }}</td>
                                    <td>₹{{ number_format($plotSale?->coupon_discount ?? 0, 2) }}</td>
                                    <td>₹{{ number_format($payableAmount, 2) }}</td>
                                    <td>₹{{ number_format($paidAmount, 2) }}</td>
                                    <td>{{ strtoupper($payment->payment_mode ?? '-') }}</td>
                                    <td>
                                        {{ $payment->transaction_category === 'booking_fee' ? 'Booking Amount' : 'Full Payment' }}
                                    </td>
                                    <td>{{ strtoupper($payment->cheque_status ?? 'CLEAR') }}</td>
                                    <td>
                                        {{ $payment->cheque_date ? \Carbon\Carbon::parse($payment->cheque_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $payment->created_at?->format('d-m-Y') }}</td>
                                    <td>{{ $payment->remark ?? '-' }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-footer">
                                <td colspan="7"></td>
                                <td>Total</td>
                                <td>₹{{ number_format($totalPayable, 2) }}</td>
                                <td class="text-danger">
                                    ₹{{ number_format($totalPaid, 2) }}
                                </td>
                                <td colspan="6"></td>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            $('#paymentTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

            function getCustomerName(customerId) {

                if (customerId != '') {

                    $.ajax({
                        url: "/get-customer-details/" + customerId,
                        type: "GET",

                        success: function(response) {
                            $('#customer_name').val(response.name);
                        }
                    });

                } else {

                    $('#customer_name').val('');

                }
            }

            $('#customer_id').change(function() {
                getCustomerName($(this).val());
            });

            let customerId = $('#customer_id').val();

            if (customerId != '') {
                getCustomerName(customerId);
            }

        });
    </script>
@endpush
