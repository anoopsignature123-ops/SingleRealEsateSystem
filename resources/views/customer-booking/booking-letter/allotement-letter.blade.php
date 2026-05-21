<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Allotment Letter</title>
    <style>
        @page {
            size: A4;
            margin: 240px 65px 30px 65px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .content {
            text-align: justify;
        }

        p {
            margin: 0 0 16px 0;
            text-indent: 0px;
        }

        .center-heading {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 16px 0;
        }

        .details-block {
            margin-top: 25px;
            line-height: 1.5;
        }

        .details-block div {
            font-weight: bold;
        }

        .signature-table,
        .witness-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table {
            margin-top: 55px;
        }

        .witness-table {
            margin-top: 45px;
        }

        .signature-table td,
        .witness-table td {
            width: 50%;
            vertical-align: top;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .party-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .signatory-info {
            font-size: 14px;
            line-height: 1.3;
        }

        .company-bottom {
            font-weight: bold;
            margin-top: 2px;
        }

        .witness-title {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>

    @php
        $plot = $booking->plotSaleDetail;
    @endphp

    <div class="content">
        <p>This PAL is made on the 21th day of May, 2026</p>

        <div class="center-heading">BETWEEN</div>

        <p>
            <strong>SANI INFRA HEIGHT</strong>, a Partnership firm within the meaning of Sani Infra Height through its
            Authorised Signatory (Here in after called First Party) which expression shall unless it be repugnant to the
            context or meaning there of shall mean and include legal representative, executors and administrators;
        </p>

        <div class="center-heading">AND</div>

        <p>
            <strong>{{ strtoupper($booking->primaryDetail?->name ?? 'SHIVAM') }}</strong>, resident of
            <strong>{{ $booking->primaryDetail?->permanent_address ?? 'SWWSSW, UP' }}</strong>(Here in after called
            Second Party) which expression shall unless it be repugnant to the context or meaning there of shall mean
            and include his/her legal representative, executors and administrators and assignee;
        </p>

        <p>
            This PAL between the above said both the parties is as per the Terms &amp; Conditions (Annexure-A) &amp;
            Payment Schedule (Annexure-B) of the attached Booking Form for the said Property Unit (Plot No -
            <strong>{{ $plot?->plotDetail?->plot_number ?? 'A-1' }}</strong> , Area
            <strong>{{ $plot?->plot_area ? number_format($plot->plot_area, 2) : '1000.00' }} Sq.ft.</strong>) as per the
            attached map of the project SANI INFRA HEIGHT of the First Party (SANI INFRA HEIGHT).
        </p>

        <p>
            An amount of Rs.
            <strong>{{ $booking->payments?->first()?->booking_amount ? number_format($booking->payments->first()->booking_amount, 2) . '/-' : '10000.00/-' }}</strong>
            - By <strong>{{ ucfirst($booking->payments?->first()?->payment_mode ?? 'Cash') }}</strong> Dated-
            <strong>{{ $booking->payments?->first()?->created_at ? $booking->payments->first()->created_at->format('d/m/Y') : '16/10/2024' }}</strong>
            received as booking amount.
        </p>

        <p>
            Where as this plot allotment letter (PAL) has been executed on this 21th day of May, 2026 between both the
            parties will fully and without any pressure in the presence of the witness. This PAL is being prepared and
            signed in the duplicate with a copy of the same available with both the parties.
        </p>

        <div class="details-block">
            <div>Place : Lucknow</div>
            <div>Dated:21th May 2026</div>
        </div>

        <table class="signature-table">
            <tr>
                <td class="text-left">
                    <div class="party-title">FIRST PARTY</div>
                    <div class="signatory-info">
                        <div>(Authorised Signatory)</div>
                        <div class="company-bottom">SANI INFRA HEIGHT</div>
                    </div>
                </td>
                <td class="text-right">
                    <div class="party-title">SECOND PARTY</div>
                    <div class="signatory-info">
                        <div>{{ strtoupper($booking->primaryDetail?->name ?? 'SHIVAM') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="witness-table">
            <tr>
                <td class="text-left">
                    <div class="witness-title">(Witness 1)</div>
                </td>
                <td class="text-right">
                    <div class="witness-title">(Witness 2)</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
