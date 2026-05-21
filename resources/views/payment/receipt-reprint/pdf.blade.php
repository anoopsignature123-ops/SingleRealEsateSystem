<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>

    <style>
        * {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
            background-color: #fff;
        }

        .page-container {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            table-layout: fixed;
        }

        .receipt-row>td {
            padding: 25px 30px;
            vertical-align: top;
        }

        .receipt-table-box {
            border: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-inner-td {
            padding: 15px;
        }

        .copy-title {
            text-align: right;
            font-size: 8.5px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .header-table td {
            vertical-align: top;
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
            line-height: 16px;
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
            letter-spacing: 0.5px;
            line-height: 14px;
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
            font-size: 8.5px;
        }

        .divider-row td {
            padding: 5px 30px !important;
        }

        .divider {
            border-top: 1px dashed #000;
            width: 100%;
            height: 1px;
        }
    </style>
</head>

<body>
    @php
        $booking = $payment->customerBooking;
        $plotSale = $booking?->plotSaleDetail;
    @endphp

    <table class="page-container">
        @foreach (['Customer Copy', 'Office Copy'] as $index => $copy)
            <tr class="receipt-row">
                <td>
                    <div class="copy-title">
                        {{ $copy }}
                    </div>
                    <table class="receipt-table-box">
                        <tr>
                            <td class="receipt-inner-td">
                                <table class="header-table">
                                    <tr>
                                        <td width="30%">
                                            <img src="{{ isset($company->logo) ? public_path('storage/' . $company->logo) : public_path('assets/images/admin.png') }}"
                                                class="logo">
                                        </td>
                                        <td width="70%" class="company-section">
                                            <div class="company-name">
                                                {{ $company->name ?? 'Sani Infra Height' }}
                                            </div>
                                            <div style="margin-top: 4px;">
                                                <strong>Email Id:</strong>
                                                {{ $company->email ?? 'SaniInfra@gmail.com' }}
                                            </div>
                                            <div>
                                                <strong>Website:</strong> {{ $company->website_link ?? 'www.abc.com' }}
                                            </div>
                                            <div>
                                                <strong>Mob:</strong> {{ $company->contact_no ?? '+91 9878789786' }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="reg-row">
                                    Registration No. : {{ $booking->customer_code ?? 'U70200UP2020PTC127030' }}
                                </div>

                                <div class="receipt-title">
                                    PAYMENT RECEIPT
                                </div>

                                <table class="top-row">
                                    <tr>
                                        <td width="50%">
                                            Receipt No : {{ $payment->receipt_number ?? 'PRS0000003' }}<br>
                                            S.No : {{ $payment->id ?? '3' }}
                                        </td>
                                        <td width="50%" align="right" valign="top">
                                            Receipt Date :
                                            {{ $payment->created_at ? $payment->created_at->format('d/m/Y') : '20/03/2026' }}
                                        </td>
                                    </tr>
                                </table>

                                <table class="details-table">
                                    <tr>
                                        <td class="label-col">Booking Id :</td>
                                        <td class="value-col">{{ $booking->booking_code ?? 'PRS0000003' }}</td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Aadhar No. :</td>
                                        <td class="value-col">[Aadhaar Redacted]</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Project :</td>
                                        <td class="value-col">{{ $plotSale?->project?->name ?? 'Rajgharana' }}</td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Plot No. :</td>
                                        <td class="value-col">{{ $plotSale?->plotDetail?->plot_number ?? 'A-1' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Block :</td>
                                        <td class="value-col">{{ $plotSale?->block?->block ?? 'A' }}</td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Area :</td>
                                        <td class="value-col">
                                            {{ $plotSale?->plot_area ? number_format($plotSale->plot_area, 2) : '1000.00' }}
                                            Sq.Ft.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Customer's Name :</td>
                                        <td class="value-col">{{ $booking->primaryDetail?->name ?? 'Santosh Kumar' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Plot Rate :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $plotSale?->plot_rate ? number_format($plotSale->plot_rate, 2) : '899.00' }}
                                            /Sq.Ft.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Address :</td>
                                        <td class="value-col">
                                            {{ $booking->primaryDetail?->permanent_address ?? 'N/A' }}</td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Payment Status :</td>
                                        <td class="value-col">{{ ucfirst($payment->payment_status ?? 'Clear') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Plot Location :</td>
                                        <td class="value-col">{{ $plotSale?->plotDetail?->location ?? 'Normal' }}</td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Plc Amount :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $plotSale?->plc_amount ? number_format($plotSale->plc_amount, 2) : '0.00' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Plot Cost :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $plotSale?->plot_cost ? number_format($plotSale->plot_cost, 2) : '899000.00' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Paid Amount :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $payment->booking_amount ? number_format($payment->booking_amount, 2) : '49000.00' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Booking Amount :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $payment->booking_amount ? number_format($payment->booking_amount, 2) : '49000.00' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">In Words (Rs.) :</td>
                                        <td class="value-col">
                                            {{ isset($payment->booking_amount) ? amountInWords($payment->booking_amount) : 'Forty Nine Thousand' }}
                                            Only
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Discount :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $plotSale?->coupon_discount ? number_format($plotSale->coupon_discount, 2) : '50000.00' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Payment Mode :</td>
                                        <td class="value-col">{{ ucfirst($payment->payment_mode ?? 'Cash') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Other Charges :</td>
                                        <td class="value-col">
                                            Rs.
                                            {{ $plotSale?->other_charges ? number_format($plotSale->other_charges, 2) : '0.00' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Payment As :</td>
                                        <td class="value-col">
                                            {{ $payment->plan_type == 'emi_plan' ? 'EMI Payment' : 'Booking Amount' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Plan Type :</td>
                                        <td class="value-col" style="color: #0288d1; font-weight: bold;">
                                            {{ $payment->plan_type == 'emi_plan' ? 'EMI Plan' : 'Full Payment' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Due Amount :</td>
                                        <td class="value-col" style="color: #d32f2f; font-weight: bold;">
                                            Rs.
                                            {{ $payment->due_amount ? number_format($payment->due_amount, 2) : '800000.00' }}
                                        </td>
                                    </tr>

                                    @if ($payment->plan_type == 'emi_plan')
                                        <tr>
                                            <td class="label-col">Total EMIs :</td>
                                            <td class="value-col">{{ $payment->emi_months ?? 'N/A' }} Months</td>
                                            <td class="space-col"></td>
                                            <td class="label-col">Per Month EMI :</td>
                                            <td class="value-col">
                                                Rs.
                                                @if ($payment->due_amount && $payment->emi_months)
                                                    {{ number_format((float) $payment->due_amount / (int) $payment->emi_months, 2) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">Next EMI Date :</td>
                                            <td class="value-col">
                                                {{ $payment->emi_date ? \Carbon\Carbon::parse($payment->emi_date)->format('d/m/Y') : 'N/A' }}
                                            </td>
                                            <td class="space-col"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    @endif

                                    @if ($payment->payment_mode == 'cheque' || $payment->payment_mode == 'neft_rtgs')
                                        <tr>
                                            <td class="label-col">Cheque/Txn No :</td>
                                            <td class="value-col">
                                                {{ $payment->cheque_number ?? ($payment->transaction_number ?? 'N/A') }}
                                            </td>
                                            <td class="space-col"></td>
                                            <td class="label-col">Bank Name :</td>
                                            <td class="value-col">{{ $payment->bank_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">Account No :</td>
                                            <td class="value-col">{{ $payment->account_number ?? 'N/A' }}</td>
                                            <td class="space-col"></td>
                                            <td class="label-col">Cheque Date :</td>
                                            <td class="value-col">
                                                {{ $payment->cheque_date ? date('d/m/Y', strtotime($payment->cheque_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($payment->payment_mode == 'dd')
                                        <tr>
                                            <td class="label-col">DD Number :</td>
                                            <td class="value-col">{{ $payment->dd_number ?? 'N/A' }}</td>
                                            <td class="space-col"></td>
                                            <td class="label-col">Bank Name :</td>
                                            <td class="value-col">{{ $payment->bank_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label-col">Account No :</td>
                                            <td class="value-col">{{ $payment->account_number ?? 'N/A' }}</td>
                                            <td class="space-col"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="label-col">Payment Date :</td>
                                        <td class="value-col">
                                            {{ $payment->created_at ? $payment->created_at->format('d/m/Y') : '20/03/2026' }}
                                        </td>
                                        <td class="space-col"></td>
                                        <td class="label-col">Over Due :</td>
                                        <td class="value-col">Rs. {{ $payment->due_amount ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Remark :</td>
                                        <td class="value-col" colspan="4">{{ $payment->remark ?? 'N/A' }}</td>
                                    </tr>
                                </table>

                                <div class="note-box">
                                    Note -<br>
                                    1- The receipt is subject to realization of cheque<br>
                                    2- All Disputes Subjected to Lucknow Jurisdiction only.<br>
                                    3- Booking / Token Amount not Refundable and not Transferable.

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
