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

                    <i class="bi bi-cash-stack me-2"></i>

                    One Time Payment Dues Report

                </h3>

                <small class="text-muted">

                    Search and export payment dues reports

                </small>

            </div>

            <span class="badge badge-report">

                Total: {{ count($payments) }}

            </span>

        </div>


        {{-- Filter --}}
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

                            <label>Select Customer</label>

                            <select name="customer_id" class="form-select">

                                <option value="">All</option>

                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->customerBooking?->customer_id }}"
                                        {{ request('customer_id') == $item->customerBooking?->customer_id ? 'selected' : '' }}>
                                        {{ $item->customerBooking?->customer_code }} -
                                        {{ $item->customerBooking?->primaryDetail?->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3">

                            <label>Customer Name</label>

                            <input type="text" name="customer_name" class="form-control"
                                value="{{ request('customer_name') }}" placeholder="Enter Customer Name">

                        </div>


                        <div class="col-md-6 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>

                            <a href="{{ route('admin.one-time-payment-dues-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>

                                Reset

                            </a>

                            <a href="{{ route('admin.one-time-payment-dues-report.export', request()->all()) }}"
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

                    Payment Due Records

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="paymentTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>Payable Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                            </tr>

                        </thead>

                        <tbody>

                            @php
                                $totalPayable = 0;
                                $totalPaid = 0;
                                $totalDue = 0;
                            @endphp

                            @foreach ($payments as $key => $payment)
                                @php
                                    $paid = $payment->booking_amount ?? 0;
                                    $payable = $payment->net_payable_amount ?? 0;
                                    $due = $payable - $paid;

                                    $totalPayable += $payable;
                                    $totalPaid += $paid;
                                    $totalDue += $due;
                                @endphp

                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        {{ $payment->customerBooking?->booking_code }}
                                    </td>

                                    <td>
                                        {{ $payment->customerBooking?->primaryDetail?->name }}
                                    </td>

                                    <td>
                                        {{ $payment->plotSaleDetail?->project?->name }}
                                    </td>

                                    <td>
                                        {{ $payment->plotSaleDetail?->plotDetail?->plot_number }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($payable, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($paid, 2) }}
                                    </td>

                                    <td class="text-danger fw-bold">
                                        ₹{{ number_format($due, 2) }}
                                    </td>

                                </tr>
                            @endforeach

                        <tfoot>

                            <tr class="fw-bold table-footer">

                                <td colspan="3"></td>

                                <td>
                                    Total
                                </td>

                                <td>
                                    ₹{{ number_format($totalPayable, 2) }}
                                </td>

                                <td>
                                    ₹{{ number_format($totalPaid, 2) }}
                                </td>

                                <td class="text-danger">
                                    ₹{{ number_format($totalDue, 2) }}
                                </td>

                            </tr>

                        </tfoot>

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

            $('#paymentTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

        });
    </script>
@endpush
