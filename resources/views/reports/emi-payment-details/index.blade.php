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

                    <i class="bi bi-credit-card me-2"></i>

                    EMI Payment Details Report

                </h3>

                <small class="text-muted">

                    Search and export EMI payment reports

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

                        {{-- Customer ID --}}
                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">

                                Customer ID

                            </label>

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


                        {{-- Customer Name --}}
                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">

                                Customer Name

                            </label>

                            <input type="text" name="customer_name" id="customer_name" class="form-control"
                                value="{{ request('customer_name') }}" readonly placeholder="Enter Custmer Name">

                        </div>


                        {{-- From Date --}}
                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">

                                From Date

                            </label>

                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">

                        </div>


                        {{-- To Date --}}
                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">

                                To Date

                            </label>

                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">

                        </div>


                        {{-- Payment Mode --}}
                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">

                                Payment Mode

                            </label>

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


                        {{-- Buttons --}}
                        <div class="col-md-4 d-flex gap-2 align-items-end">

                            <button class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>


                            <a href="{{ route('admin.emi-payment-details-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>

                                Reset

                            </a>


                            <a href="{{ route('admin.emi-payment-details-report.export', request()->all()) }}"
                                class="btn btn-success">

                                <i class="bi bi-file-earmark-excel me-1"></i>

                                Export

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>



        {{-- Table Card --}}
        <div class="card report-card">

            <div class="report-header">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-table me-2"></i>

                    EMI Payment Records

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="emiPaymentTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>

                                <th>Sr.No</th>
                                <th>Booking Id</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>Inst Amount</th>
                                <th>Paid Amount</th>
                                <th>Pay Mode</th>
                                <th>Status</th>
                                <th>Pay Date</th>
                                <th>Remark</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($payments as $key => $payment)
                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $payment->customerBooking?->booking_code }}</td>

                                    <td>{{ $payment->customerBooking?->primaryDetail?->name }}</td>

                                    <td>{{ $payment->plotSaleDetail?->project?->name }}</td>

                                    <td>{{ $payment->plotSaleDetail?->plotDetail?->plot_number }}</td>

                                    <td>
                                        ₹{{ number_format($payment->after_booking_payable_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($payment->booking_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($payment->payment_mode ?? '-') }}
                                    </td>

                                    <td>

                                        <span class="badge bg-success">

                                            {{ strtoupper($payment->cheque_status ?? 'CLEAR') }}

                                        </span>

                                    </td>

                                    <td>
                                        {{ $payment->created_at?->format('d-m-Y') }}
                                    </td>

                                    <td>
                                        {{ $payment->remark ?? '-' }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(function() {

            $('#emiPaymentTable').DataTable({
                pageLength: 10,
                scrollX: true
            });


            $('#customer_id').change(function() {

                let customerId = $(this).val();

                if (customerId != '') {

                    $.ajax({

                        url: "/admin/get-customer-details/" + customerId,
                        type: "GET",

                        success: function(response) {

                            $('#customer_name').val(response.name);

                        }

                    });

                } else {

                    $('#customer_name').val('');

                }

            });

        });
    </script>
@endpush
