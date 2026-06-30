<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
        }

        .page-container {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-row > td {
            padding: 25px 30px;
            vertical-align: top;
        }

        .receipt-box {
            border: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-inner {
            padding: 15px;
        }

        .copy-title {
            text-align: right;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .logo {
            width: 65px;
        }

        .company-section {
            text-align: right;
            line-height: 12px;
        }

        .company-name {
            font-size: 15px;
            font-weight: bold;
        }

        .reg-row {
            font-weight: bold;
            margin-top: 5px;
            font-size: 10px;
        }

        .receipt-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            margin: 8px 0;
            letter-spacing: .5px;
        }

        .top-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .top-row td {
            font-weight: bold;
            font-size: 9px;
            line-height: 13px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .details-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 9px;
            word-wrap: break-word;
        }

        .plot-detail-table {
            border-collapse: collapse;
            margin: 4px 0 6px;
            width: 100%;
        }

        .plot-detail-table th {
            background: #f2f2f2;
            border: 1px solid #333;
            font-size: 7.6px;
            font-weight: bold;
            padding: 4px 3px;
            text-align: left;
        }

        .plot-detail-table td {
            border: 1px solid #333;
            font-size: 7.6px;
            padding: 4px 3px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .label-col {
            font-weight: bold;
            width: 18%;
            font-size: 8.5px;
        }

        .value-col {
            font-weight: bold;
            width: 30%;
        }

        .space-col {
            width: 4%;
        }

        .note-box {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 6px;
            font-size: 8.5px;
            line-height: 12px;
            font-weight: bold;
        }

        .signature {
            text-align: right;
            margin-top: 5px;
            font-weight: bold;
        }

        .divider-row td {
            padding: 5px 30px !important;
        }

        .divider {
            border-top: 1px dashed #000;
            height: 1px;
        }
    </style>
</head>

<body>
@php
    $booking = $payment->customerBooking;
    $plotSale = $payment->plotSaleDetail;

    $receiptPayments = ($receiptPayments ?? collect([$payment]))->values();
    $receiptTotals = $receiptTotals ?? [
        'paid' => (float) ($payment->paid_amount ?? $payment->booking_amount ?? 0),
        'due' => (float) ($payment->due_amount ?? 0),
        'total_cost' => (float) ($plotSale?->total_plot_cost ?? 0),
        'plot_count' => 1,
    ];
    $paidAmount = (float) $receiptTotals['paid'];
    $totalCost = (float) $receiptTotals['total_cost'];
    $dueAmount = (float) $receiptTotals['due'];

    $paymentAs = 'Payment';
    if ($payment->transaction_category === 'booking_fee') {
        $paymentAs = 'Booking Amount';
    } elseif ($payment->transaction_category === 'emi_payment') {
        $paymentAs = 'EMI Payment';
    } elseif ($payment->transaction_category === 'one_time') {
        $paymentAs = 'One Time Payment';
    }

    $logoPath = isset($company->logo)
        ? public_path('storage/' . $company->logo)
        : public_path('assets/images/admin.png');
@endphp

<table class="page-container">
    @foreach (['Customer Copy', 'Office Copy'] as $index => $copy)
        <tr class="receipt-row">
            <td>
                <div class="copy-title">{{ $copy }}</div>

                <table class="receipt-box">
                    <tr>
                        <td class="receipt-inner">

                            <table class="header-table">
                                <tr>
                                    <td width="30%">
                                        <img src="{{ $logoPath }}" class="logo">
                                    </td>

                                    <td width="70%" class="company-section">
                                        <div class="company-name">
                                            {{ $company->name ?? 'Company Name' }}
                                        </div>

                                        <div>
                                            <strong>Email:</strong>
                                            {{ $company->email ?? 'N/A' }}
                                        </div>

                                        <div>
                                            <strong>Website:</strong>
                                            {{ $company->website_link ?? 'N/A' }}
                                        </div>

                                        <div>
                                            <strong>Mob:</strong>
                                            {{ $company->contact_no ?? 'N/A' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div class="reg-row">
                                Customer No. : {{ $booking?->customer_code ?? 'N/A' }}
                            </div>

                            <div class="receipt-title">
                                PAYMENT RECEIPT
                            </div>

                            <table class="top-row">
                                <tr>
                                    <td width="50%">
                                        Receipt No : {{ $payment->receipt_number ?? 'N/A' }}<br>
                                        S.No : {{ $payment->id }}
                                    </td>

                                    <td width="50%" align="right">
                                        Receipt Date :
                                        {{ $payment->created_at ? $payment->created_at->format('d/m/Y') : 'N/A' }}
                                    </td>
                                </tr>
                            </table>

                            <table class="details-table">
                                <tr>
                                    <td class="label-col">Booking Id :</td>
                                    <td class="value-col">
                                        {{ $plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Project :</td>
                                    <td class="value-col">
                                        {{ $plotSale?->project?->name ?? 'N/A' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Block :</td>
                                    <td class="value-col">
                                        {{ $receiptPayments->pluck('plotSaleDetail.block.block')->filter()->unique()->implode(', ') ?: 'N/A' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Plot No. :</td>
                                    <td class="value-col">
                                        {{ $receiptPayments->pluck('plotSaleDetail.plotDetail.plot_number')->filter()->unique()->implode(', ') ?: 'N/A' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Customer Name :</td>
                                    <td class="value-col">
                                        {{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Area :</td>
                                    <td class="value-col">
                                        {{ number_format((float) $receiptPayments->pluck('plotSaleDetail')->filter()->sum('plot_area'), 2) }} Sq.Ft.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Address :</td>
                                    <td class="value-col">
                                        {{ $booking?->primaryDetail?->permanent_address ?? 'N/A' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Plot Rate :</td>
                                    <td class="value-col">
                                        {{ $receiptTotals['plot_count'] > 1 ? 'Multiple' : 'Rs. '.number_format((float) ($plotSale?->plot_rate ?? 0), 2).'/Sq.Ft.' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Plot Cost :</td>
                                    <td class="value-col">
                                        Rs. {{ number_format((float) $receiptPayments->pluck('plotSaleDetail')->filter()->sum('plot_cost'), 2) }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">PLC Amount :</td>
                                    <td class="value-col">
                                        Rs. {{ number_format((float) $receiptPayments->pluck('plotSaleDetail')->filter()->sum('plc_amount'), 2) }}
                                    </td>
                                </tr>

                                @if (($receiptTotals['plot_count'] ?? 1) > 1)
                                    <tr>
                                        <td class="label-col">Plot Details :</td>
                                        <td class="value-col" colspan="4">
                                            <table class="plot-detail-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Project</th>
                                                        <th>Block</th>
                                                        <th>Plot</th>
                                                        <th class="text-right">Area</th>
                                                        <th class="text-right">Rate</th>
                                                        <th class="text-right">Plot Cost</th>
                                                        <th class="text-right">PLC</th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right">Paid</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($receiptPayments as $rowPayment)
                                                        @php
                                                            $rowPlotSale = $rowPayment->plotSaleDetail;
                                                            $rowPaid = (float) ($rowPayment->paid_amount ?? $rowPayment->booking_amount ?? 0);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $rowPlotSale?->project?->name ?? 'N/A' }}</td>
                                                            <td>{{ $rowPlotSale?->block?->block ?? 'N/A' }}</td>
                                                            <td>{{ $rowPlotSale?->plotDetail?->plot_number ?? 'N/A' }}</td>
                                                            <td class="text-right">{{ number_format((float) ($rowPlotSale?->plot_area ?? 0), 2) }}</td>
                                                            <td class="text-right">{{ number_format((float) ($rowPlotSale?->plot_rate ?? 0), 2) }}</td>
                                                            <td class="text-right">{{ number_format((float) ($rowPlotSale?->plot_cost ?? 0), 2) }}</td>
                                                            <td class="text-right">{{ number_format((float) ($rowPlotSale?->plc_amount ?? 0), 2) }}</td>
                                                            <td class="text-right">{{ number_format((float) ($rowPlotSale?->total_plot_cost ?? 0), 2) }}</td>
                                                            <td class="text-right">{{ number_format($rowPaid, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="label-col">Total Cost :</td>
                                    <td class="value-col">
                                        Rs. {{ number_format($totalCost, 2) }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Paid Amount :</td>
                                    <td class="value-col">
                                        Rs. {{ number_format($paidAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">In Words :</td>
                                    <td class="value-col">
                                        {{ amountInWords($paidAmount) }} Only
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Due Amount :</td>
                                    <td class="value-col">
                                        Rs. {{ number_format($dueAmount, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Payment Mode :</td>
                                    <td class="value-col">
                                        {{ strtoupper(str_replace('_', ' / ', $payment->payment_mode ?? 'N/A')) }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Payment As :</td>
                                    <td class="value-col">
                                        {{ $paymentAs }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Plan Type :</td>
                                    <td class="value-col">
                                        {{ $payment->plan_type === 'emi_plan' ? 'EMI Plan' : 'Full Payment' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Payment Status :</td>
                                    <td class="value-col">
                                        {{ ucfirst($payment->payment_status ?? 'N/A') }}
                                    </td>
                                </tr>

                                @if ($payment->plan_type === 'emi_plan')
                                    <tr>
                                        <td class="label-col">EMI Months :</td>
                                        <td class="value-col">
                                            {{ $payment->emi_months ?? 'N/A' }}
                                        </td>

                                        <td class="space-col"></td>

                                        <td class="label-col">Monthly EMI :</td>
                                        <td class="value-col">
                                            Rs. {{ number_format((float) ($payment->after_booking_payable_amount ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endif

                                @if (in_array($payment->payment_mode, ['cheque', 'dd', 'neft_rtgs', 'card']))
                                    <tr>
                                        <td class="label-col">Bank Name :</td>
                                        <td class="value-col">
                                            {{ $payment->bank_name ?? 'N/A' }}
                                        </td>

                                        <td class="space-col"></td>

                                        <td class="label-col">Account No :</td>
                                        <td class="value-col">
                                            {{ $payment->account_number ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endif

                                @if ($payment->payment_mode === 'cheque')
                                    <tr>
                                        <td class="label-col">Cheque No :</td>
                                        <td class="value-col">
                                            {{ $payment->cheque_number ?? 'N/A' }}
                                        </td>

                                        <td class="space-col"></td>

                                        <td class="label-col">Cheque Date :</td>
                                        <td class="value-col">
                                            {{ $payment->cheque_date ? date('d/m/Y', strtotime($payment->cheque_date)) : 'N/A' }}
                                        </td>
                                    </tr>
                                @endif

                                @if ($payment->payment_mode === 'dd')
                                    <tr>
                                        <td class="label-col">DD Number :</td>
                                        <td class="value-col">
                                            {{ $payment->dd_number ?? 'N/A' }}
                                        </td>

                                        <td class="space-col"></td>

                                        <td class="label-col">Branch :</td>
                                        <td class="value-col">
                                            {{ $payment->branch_name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endif

                                @if (in_array($payment->payment_mode, ['neft_rtgs', 'card']))
                                    <tr>
                                        <td class="label-col">Transaction No :</td>
                                        <td class="value-col">
                                            {{ $payment->transaction_number ?? 'N/A' }}
                                        </td>

                                        <td class="space-col"></td>

                                        <td class="label-col">Branch :</td>
                                        <td class="value-col">
                                            {{ $payment->branch_name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="label-col">Payment Date :</td>
                                    <td class="value-col">
                                        {{ $payment->created_at ? $payment->created_at->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    <td class="space-col"></td>

                                    <td class="label-col">Remark :</td>
                                    <td class="value-col">
                                        {{ $payment->remark ?? 'N/A' }}
                                    </td>
                                </tr>
                            </table>

                            <div class="note-box">
                                Note -<br>
                                1- The receipt is subject to realization of cheque.<br>
                                2- All disputes are subject to Lucknow jurisdiction only.<br>
                                3- Booking / token amount is not refundable and not transferable.

                                <div class="signature">
                                    (Authorised Signature)
                                </div>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        @if ($index == 0)
            <tr class="divider-row">
                <td>
                    <div class="divider"></div>
                </td>
            </tr>
        @endif
    @endforeach
</table>

</body>
</html>
