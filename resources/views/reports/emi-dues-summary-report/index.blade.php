@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-calendar2-week me-2"></i>EMI Dues Summary
                </h3>
                <small class="text-muted">
                    EMI dues summary report
                </small>
            </div>
            <span class="badge badge-report">EMI Summary</span>
        </div>
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>Filter Report
                </h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Sponsor ID</label>
                            <select name="sponsor_id" class="form-select">
                                <option value="">Select Sponsor</option>
                                @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}"
                                        {{ request('sponsor_id') == $sponsor->id ? 'selected' : '' }}>
                                        {{ $sponsor->associate_id }} - {{ $sponsor->associate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Till Date</label>
                            <input type="date" name="till_date" class="form-control"
                                value="{{ request('till_date', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Due EMI</label>
                            <select name="due_emi" class="form-select">
                                <option value="">All</option>
                                <option value="greater" {{ request('due_emi') == 'greater' ? 'selected' : '' }}>Greater Than
                                    0
                                </option>
                                <option value="equal" {{ request('due_emi') == 'equal' ? 'selected' : '' }}>Equal To 0
                                </option>
                                <option value="less" {{ request('due_emi') == 'less' ? 'selected' : '' }}>Less Than 0
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Customer ID</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }}
                                        /
                                        {{ $customer->primaryDetail?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 d-flex gap-2">

                            <button type="submit" name="search" value="1" class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>
                                Search

                            </button>

                            <a href="{{ route('emi-dues-summary-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset

                            </a>

                            {{-- <a href="{{ route('emi-dues-summary-report.export', request()->all()) }}"
                                class="btn btn-success">

                                <i class="bi bi-file-earmark-excel me-1"></i>

                                Export

                            </a> --}}

                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- Table --}}
        @if (request()->has('search'))
            <div class="card report-card">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>
                        EMI Dues Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="emiSummaryTable" class="table table-hover align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Booking Id</th>
                                    <th>Customer Id</th>
                                    <th>Customer Name</th>
                                    <th>Booking Date</th>
                                    <th>Block</th>
                                    <th>Plot No</th>
                                    <th>Plot Rate</th>
                                    <th>Plot Area</th>
                                    <th>Plot Cost</th>
                                    <th>PLC Amt</th>
                                    <th>Payable Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Installment Amount</th>
                                    <th>Months</th>
                                    <th>Emi Till Date</th>
                                    <th>No Of Emi Till Date</th>
                                    <th>No Of Emi Paid</th>
                                    <th>Agent Id.</th>
                                    <th>Dues Emi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $key => $report)
                                    @php
                                        $plotSale = $report->plotSaleDetail;
                                        $payment = $report->payment;
                                        $payableAmount = (float) ($payment?->net_payable_amount ?? 0);
                                        $paidAmount = (float) $report->payments
                                            ->whereIn('payment_status', ['paid', 'cleared'])
                                            ->sum('paid_amount');
                                        $months = (int) ($payment?->emi_months ?? 0);
                                        $installmentAmount = 0;
                                        if ($months > 0) {
                                            $installmentAmount = $payableAmount / $months;
                                        }
                                        $bookingDate = $plotSale?->booking_date;
                                        $tillDate = request('till_date')
                                            ? \Carbon\Carbon::parse(request('till_date'))
                                            : now();
                                        $emiTillDate = 0;
                                        if ($bookingDate) {
                                            $bookingCarbon = \Carbon\Carbon::parse($bookingDate);
                                            $emiTillDate = $bookingCarbon->diffInMonths($tillDate);
                                            if ($emiTillDate > $months) {
                                                $emiTillDate = $months;
                                            }
                                        }
                                        $noOfPaidEmi = 0;
                                        if ($installmentAmount > 0) {
                                            $noOfPaidEmi = floor($paidAmount / $installmentAmount);
                                        }
                                        $duesEmi = $emiTillDate - $noOfPaidEmi;
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $report->booking_code ?? 'N/A' }}</td>
                                        <td> {{ $report->customer_code ?? 'N/A' }} </td>
                                        <td>{{ $report->primaryDetail?->name ?? 'N/A' }}</td>
                                        <td>{{ $bookingDate ? \Carbon\Carbon::parse($bookingDate)->format('d-m-Y') : 'N/A' }}
                                        </td>
                                        <td>{{ $plotSale?->block?->block ?? 'N/A' }}</td>
                                        <td>{{ $plotSale?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($plotSale?->plot_rate ?? 0, 2) }}</td>
                                        <td>{{ $plotSale?->plot_area ?? 0 }}</td>
                                        <td>₹{{ number_format($plotSale?->plot_cost ?? 0, 2) }}</td>
                                        <td>₹{{ number_format($plotSale?->plc_amount ?? 0, 2) }}</td>
                                        <td class="fw-bold">₹{{ number_format($payableAmount, 2) }}</td>
                                        <td class="text-success fw-bold">₹{{ number_format($paidAmount, 2) }}</td>
                                        <td>₹{{ number_format($installmentAmount, 2) }}</td>
                                        <td>{{ $months }}</td>
                                        <td>{{ request('till_date', now()->format('Y-m-d')) }}</td>
                                        <td>{{ $emiTillDate }}</td>
                                        <td> {{ $noOfPaidEmi }}</td>
                                        <td>{{ $report->associate?->associate_id ?? 'N/A' }}</td>
                                        <td class="{{ $duesEmi > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                            {{ (int) $duesEmi }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
@push('scripts')
    <script>
        $(function() {
            $('#emiSummaryTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
