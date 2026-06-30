<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        @page { size: A4; margin: 22px; }
        * { box-sizing: border-box; }
        body {
            background: {{ $theme['page_bg'] }};
            color: #111827;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10.5px;
            margin: 0;
        }
        .sheet {
            background: #ffffff;
            border: 1px solid {{ $theme['border'] }};
            min-height: 1012px;
            padding: 0;
            position: relative;
        }
        .header {
            background: {{ $theme['header_bg'] }};
            border-bottom: 5px solid {{ $theme['accent'] }};
            color: {{ $theme['header_text'] }};
            min-height: 112px;
            padding: 24px 32px 18px;
            position: relative;
        }
        .header:after {
            background: {{ $theme['accent'] }};
            content: "";
            height: 112px;
            position: absolute;
            right: 48px;
            top: 0;
            transform: skewX(-28deg);
            width: 34px;
        }
        .brand-table { border-collapse: collapse; position: relative; width: 100%; z-index: 1; }
        .logo-box {
            background: #ffffff;
            border-radius: 6px;
            height: 58px;
            text-align: center;
            vertical-align: middle;
            width: 86px;
        }
        .logo { max-height: 50px; max-width: 78px; }
        .company { padding-left: 14px; }
        .company strong { display: block; font-size: 21px; line-height: 1.2; }
        .company span { display: block; font-size: 8.7px; line-height: 1.45; margin-top: 4px; opacity: .86; }
        .title { font-size: 28px; font-weight: bold; letter-spacing: .8px; text-align: right; text-transform: uppercase; }
        .subtitle { font-size: 9px; margin-top: 4px; text-align: right; }
        .body { padding: 26px 32px 0; }
        .summary { border-collapse: collapse; width: 100%; }
        .summary td {
            background: {{ $theme['soft'] }};
            border: 1px solid {{ $theme['border'] }};
            padding: 10px 12px;
            vertical-align: top;
            width: 25%;
        }
        .label {
            color: #64748b;
            display: block;
            font-size: 8.8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .value {
            color: #111827;
            display: block;
            font-size: 11px;
            font-weight: bold;
            margin-top: 3px;
        }
        .amount .value {
            color: {{ $theme['accent'] }};
            font-size: 14px;
        }
        .section {
            color: {{ $theme['accent'] }};
            font-size: 12px;
            font-weight: bold;
            letter-spacing: .2px;
            margin: 22px 0 9px;
            text-transform: uppercase;
        }
        .section span {
            background: {{ $theme['accent'] }};
            display: inline-block;
            height: 8px;
            margin-right: 8px;
            width: 28px;
        }
        .grid, .details, .totals { border-collapse: collapse; width: 100%; }
        .grid td {
            border: 1px solid {{ $theme['border'] }};
            padding: 9px;
            vertical-align: top;
            width: 25%;
        }
        .details th {
            background: {{ $theme['table_head'] }};
            color: {{ $theme['table_text'] }};
            font-size: 9.4px;
            padding: 9px;
            text-align: left;
            text-transform: uppercase;
        }
        .details td {
            border: 1px solid {{ $theme['border'] }};
            padding: 9px;
            vertical-align: top;
        }
        .muted {
            color: #64748b;
            display: block;
            font-size: 8.5px;
            margin-top: 3px;
        }
        .right { text-align: right; }
        .center { text-align: center; }
        .terms-total { border-collapse: collapse; margin-top: 20px; width: 100%; }
        .terms-box {
            background: #f8fafc;
            border: 1px solid {{ $theme['border'] }};
            line-height: 1.55;
            padding: 12px;
            vertical-align: top;
            width: 55%;
        }
        .total-box { vertical-align: top; width: 45%; }
        .totals td {
            border: 1px solid {{ $theme['border'] }};
            padding: 8px 10px;
        }
        .totals .grand td {
            background: {{ $theme['accent'] }};
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
        }
        .words {
            background: {{ $theme['soft'] }};
            border: 1px solid {{ $theme['border'] }};
            font-weight: bold;
            line-height: 1.45;
            margin-top: 10px;
            padding: 9px 10px;
        }
        .footer {
            bottom: 0;
            left: 0;
            position: absolute;
            right: 0;
        }
        .signature {
            padding: 26px 32px 18px;
            text-align: right;
        }
        .signature span {
            border-top: 1px solid #111827;
            display: inline-block;
            font-weight: bold;
            padding-top: 8px;
            width: 170px;
        }
        .footer-strip {
            background: {{ $theme['header_bg'] }};
            border-top: 4px solid {{ $theme['accent'] }};
            color: {{ $theme['header_text'] }};
            font-size: 8.8px;
            padding: 10px 32px;
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
        'total_cost' => (float) ($plotSale?->total_plot_cost ?? $plotSale?->final_payable ?? 0),
        'plot_count' => 1,
    ];
    $paidAmount = (float) $receiptTotals['paid'];
    $totalCost = (float) $receiptTotals['total_cost'];
    $dueAmount = (float) $receiptTotals['due'];
    $paymentAs = match ($payment->transaction_category) {
        'booking_fee' => 'Booking Amount',
        'emi_payment' => 'EMI Payment',
        'one_time' => 'One Time Payment',
        default => 'Payment',
    };
    $logoPath = isset($company->logo) ? public_path('storage/' . $company->logo) : public_path('assets/images/admin.png');
@endphp

<div class="sheet">
    <div class="header">
        <table class="brand-table">
            <tr>
                <td class="logo-box"><img src="{{ $logoPath }}" class="logo"></td>
                <td class="company">
                    <strong>{{ $company->name ?? 'Real Estate Company' }}</strong>
                    <span>{{ $company->address ?? 'Company Address' }}<br>{{ $company->email ?? 'N/A' }} | {{ $company->contact_no ?? 'N/A' }}</span>
                </td>
                <td width="34%">
                    <div class="title">{{ $theme['title'] }}</div>
                    <div class="subtitle">Payment Acknowledgement</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="body">
        <table class="summary">
            <tr>
                <td><span class="label">Receipt No</span><span class="value">{{ $payment->receipt_number ?? 'N/A' }}</span></td>
                <td><span class="label">Receipt Date</span><span class="value">{{ $payment->created_at?->format('d-M-Y') ?? 'N/A' }}</span></td>
                <td><span class="label">Payment Mode</span><span class="value">{{ strtoupper(str_replace('_', ' / ', $payment->payment_mode ?? 'N/A')) }}</span></td>
                <td class="amount"><span class="label">Paid Amount</span><span class="value">Rs. {{ number_format($paidAmount, 2) }}</span></td>
            </tr>
        </table>

        <div class="section"><span></span>Customer Information</div>
        <table class="grid">
            <tr>
                <td><span class="label">Customer ID</span><span class="value">{{ $booking?->customer_code ?? 'N/A' }}</span></td>
                <td><span class="label">Customer Name</span><span class="value">{{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}</span></td>
                <td><span class="label">Phone</span><span class="value">{{ $booking?->primaryDetail?->phone ?? $booking?->mobile ?? 'N/A' }}</span></td>
                <td><span class="label">Total Plots</span><span class="value">{{ $receiptTotals['plot_count'] ?? 1 }}</span></td>
            </tr>
            <tr>
                <td colspan="4"><span class="label">Address</span><span class="value">{{ $booking?->primaryDetail?->permanent_address ?? 'N/A' }}</span></td>
            </tr>
        </table>

        <div class="section"><span></span>Plot & Payment Details</div>
        <table class="details">
            <thead>
                <tr>
                    <th width="28%">Plot Detail</th>
                    <th width="12%" class="center">Area</th>
                    <th width="12%" class="right">Rate</th>
                    <th width="14%" class="right">Plot Cost</th>
                    <th width="10%" class="right">PLC</th>
                    <th width="12%" class="right">Total</th>
                    <th width="12%" class="right">Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receiptPayments as $rowPayment)
                    @php
                        $rowPlotSale = $rowPayment->plotSaleDetail;
                        $rowPaid = (float) ($rowPayment->paid_amount ?? $rowPayment->booking_amount ?? 0);
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $rowPlotSale?->project?->name ?? 'Project N/A' }}</strong>
                            <span class="muted">Booking: {{ $rowPlotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }} | Block: {{ $rowPlotSale?->block?->block ?? 'N/A' }} | Plot: {{ $rowPlotSale?->plotDetail?->plot_number ?? 'N/A' }}</span>
                        </td>
                        <td class="center">{{ number_format((float) ($rowPlotSale?->plot_area ?? 0), 2) }} Sq.Ft.</td>
                        <td class="right">Rs. {{ number_format((float) ($rowPlotSale?->plot_rate ?? 0), 2) }}</td>
                        <td class="right">Rs. {{ number_format((float) ($rowPlotSale?->plot_cost ?? 0), 2) }}</td>
                        <td class="right">Rs. {{ number_format((float) ($rowPlotSale?->plc_amount ?? 0), 2) }}</td>
                        <td class="right">Rs. {{ number_format((float) ($rowPlotSale?->total_plot_cost ?? 0), 2) }}</td>
                        <td class="right">Rs. {{ number_format($rowPaid, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>{{ $paymentAs }}</strong><span class="muted">{{ $payment->plan_type === 'emi_plan' ? 'EMI Plan' : 'Full Payment' }}</span></td>
                    <td class="center">-</td>
                    <td class="right">-</td>
                    <td class="right">-</td>
                    <td class="right">-</td>
                    <td class="right">Due Amount</td>
                    <td class="right">Rs. {{ number_format($dueAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section"><span></span>Payment Method</div>
        <table class="grid">
            <tr>
                <td><span class="label">Bank</span><span class="value">{{ $payment->bank_name ?? 'N/A' }}</span></td>
                <td><span class="label">Branch</span><span class="value">{{ $payment->branch_name ?? 'N/A' }}</span></td>
                <td><span class="label">Reference</span><span class="value">{{ $payment->transaction_number ?? $payment->cheque_number ?? $payment->dd_number ?? 'N/A' }}</span></td>
                <td><span class="label">Payment Date</span><span class="value">{{ $payment->created_at?->format('d-M-Y') ?? 'N/A' }}</span></td>
            </tr>
        </table>

        <table class="terms-total">
            <tr>
                <td class="terms-box">
                    <strong>Terms And Conditions</strong><br>
                    1. Receipt is subject to realization of cheque/DD.<br>
                    2. Booking/token amount is not refundable and not transferable.<br>
                    3. All disputes are subject to Lucknow jurisdiction only.
                    <div class="words">Amount In Words: {{ amountInWords($paidAmount) }} Only</div>
                </td>
                <td width="4%"></td>
                <td class="total-box">
                    <table class="totals">
                        <tr><td>Total Plot Cost</td><td class="right">Rs. {{ number_format($totalCost, 2) }}</td></tr>
                        <tr><td>Paid Amount</td><td class="right">Rs. {{ number_format($paidAmount, 2) }}</td></tr>
                        <tr><td>Due Amount</td><td class="right">Rs. {{ number_format($dueAmount, 2) }}</td></tr>
                        <tr class="grand"><td>Receipt Total</td><td class="right">Rs. {{ number_format($paidAmount, 2) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="signature"><span>Authorised Signature</span></div>
        <div class="footer-strip">Thank you for your business. This is a computer generated receipt.</div>
    </div>
</div>
</body>
</html>
