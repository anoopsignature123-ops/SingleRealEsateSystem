<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <style>
        @page { size: A4; margin: 24px; }
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; margin: 0; }
        .copy { background: #ffffff; border: 1px solid #b7dfc6; border-radius: 8px; margin-bottom: 18px; padding: 14px; }
        .copy-title { font-size: 10px; font-weight: bold; text-align: right; text-transform: uppercase; }
        .head { background: #ecfbf2; border-bottom: 2px solid #145f3b; margin-bottom: 10px; padding: 8px; width: 100%; }
        .logo { width: 62px; }
        .company { text-align: right; }
        .company strong { color: #145f3b; display: block; font-size: 16px; }
        .title { background: #145f3b; border-radius: 6px; color: #ffffff; font-size: 14px; font-weight: bold; margin: 8px 0; padding: 7px; text-align: center; }
        .grid { border-collapse: collapse; width: 100%; }
        .grid td { border: 1px solid #d1d5db; padding: 6px; vertical-align: top; width: 25%; }
        .label { color: #4b5563; display: block; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .value { display: block; font-size: 10px; font-weight: bold; margin-top: 2px; }
        .amount { background: #ecfbf2; }
        .amount .value { color: #145f3b; font-size: 12px; }
        .note { font-size: 8.5px; line-height: 1.45; margin-top: 9px; }
        .sign { margin-top: 15px; text-align: right; }
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
        'plot_count' => 1,
    ];
    $paidAmount = (float) $receiptTotals['paid'];
    $paymentAs = match ($payment->transaction_category) {
        'booking_fee' => 'Booking Amount',
        'emi_payment' => 'EMI Payment',
        'one_time' => 'One Time Payment',
        default => 'Payment',
    };
    $logoPath = isset($company->logo) ? public_path('storage/' . $company->logo) : public_path('assets/images/admin.png');
@endphp

@foreach (['Customer Copy', 'Office Copy'] as $copy)
    <div class="copy">
        <div class="copy-title">{{ $copy }}</div>
        <table class="head">
            <tr>
                <td width="20%"><img src="{{ $logoPath }}" class="logo"></td>
                <td width="80%" class="company">
                    <strong>{{ $company->name ?? 'Company Name' }}</strong>
                    <div>{{ $company->address ?? 'N/A' }}</div>
                    <div>{{ $company->email ?? 'N/A' }} | {{ $company->contact_no ?? 'N/A' }}</div>
                </td>
            </tr>
        </table>

        <div class="title">PAYMENT RECEIPT</div>

        <table class="grid">
            <tr>
                <td><span class="label">Receipt No</span><span class="value">{{ $payment->receipt_number ?? 'N/A' }}</span></td>
                <td><span class="label">Receipt Date</span><span class="value">{{ $payment->created_at?->format('d/m/Y') ?? 'N/A' }}</span></td>
                <td><span class="label">Total Plots</span><span class="value">{{ $receiptTotals['plot_count'] ?? 1 }}</span></td>
                <td class="amount"><span class="label">Paid Amount</span><span class="value">Rs. {{ number_format($paidAmount, 2) }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Customer</span><span class="value">{{ $booking?->primaryDetail?->name ?? $booking?->customer_name ?? 'N/A' }}</span></td>
                <td><span class="label">Booking ID</span><span class="value">{{ $plotSale?->booking_code ?? $booking?->booking_code ?? 'N/A' }}</span></td>
                <td><span class="label">Project</span><span class="value">{{ $plotSale?->project?->name ?? 'N/A' }}</span></td>
                <td><span class="label">Customer ID</span><span class="value">{{ $booking?->customer_code ?? 'N/A' }}</span></td>
            </tr>
            @foreach ($receiptPayments as $rowPayment)
                @php
                    $rowPlotSale = $rowPayment->plotSaleDetail;
                    $rowPaid = (float) ($rowPayment->paid_amount ?? $rowPayment->booking_amount ?? 0);
                @endphp
                <tr>
                    <td colspan="2"><span class="label">Plot Detail</span><span class="value">{{ $rowPlotSale?->project?->name ?? 'N/A' }} / {{ $rowPlotSale?->block?->block ?? 'N/A' }} / Plot {{ $rowPlotSale?->plotDetail?->plot_number ?? 'N/A' }}</span></td>
                    <td><span class="label">Area / Rate</span><span class="value">{{ number_format((float) ($rowPlotSale?->plot_area ?? 0), 2) }} Sq.Ft. / Rs. {{ number_format((float) ($rowPlotSale?->plot_rate ?? 0), 2) }}</span></td>
                    <td><span class="label">Total / Paid</span><span class="value">Rs. {{ number_format((float) ($rowPlotSale?->total_plot_cost ?? 0), 2) }} / Rs. {{ number_format($rowPaid, 2) }}</span></td>
                </tr>
                <tr>
                    <td><span class="label">Plot Cost</span><span class="value">Rs. {{ number_format((float) ($rowPlotSale?->plot_cost ?? 0), 2) }}</span></td>
                    <td><span class="label">PLC Amount</span><span class="value">Rs. {{ number_format((float) ($rowPlotSale?->plc_amount ?? 0), 2) }}</span></td>
                    <td><span class="label">Other Charges</span><span class="value">Rs. {{ number_format((float) ($rowPlotSale?->other_charges ?? 0), 2) }}</span></td>
                    <td><span class="label">Discount</span><span class="value">Rs. {{ number_format((float) ($rowPlotSale?->coupon_discount ?? 0), 2) }}</span></td>
                </tr>
            @endforeach
            <tr>
                <td><span class="label">Payment Mode</span><span class="value">{{ strtoupper(str_replace('_', ' / ', $payment->payment_mode ?? 'N/A')) }}</span></td>
                <td><span class="label">Payment As</span><span class="value">{{ $paymentAs }}</span></td>
                <td><span class="label">Status</span><span class="value">{{ ucfirst($payment->payment_status ?? 'N/A') }}</span></td>
                <td><span class="label">Due Amount</span><span class="value">Rs. {{ number_format((float) ($receiptTotals['due'] ?? $payment->due_amount ?? 0), 2) }}</span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Amount In Words</span><span class="value">{{ amountInWords($paidAmount) }} Only</span></td>
                <td><span class="label">Bank</span><span class="value">{{ $payment->bank_name ?? 'N/A' }}</span></td>
                <td><span class="label">Reference</span><span class="value">{{ $payment->transaction_number ?? $payment->cheque_number ?? $payment->dd_number ?? 'N/A' }}</span></td>
            </tr>
        </table>

        <div class="note">
            Note: Receipt is subject to realization of cheque/DD. All disputes are subject to Lucknow jurisdiction only.
        </div>
        <div class="sign"><strong>Authorised Signature</strong></div>
    </div>
@endforeach
</body>
</html>
