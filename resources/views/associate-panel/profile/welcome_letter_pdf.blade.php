<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome Letter</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            margin: 30px 40px;
            line-height: 1.6;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .company-subtitle {
            font-size: 12px;
            color: #555;
            margin-bottom: 20px;
        }

        .title-container {
            text-align: center;
            margin-bottom: 35px;
        }

        .letter-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            display: inline-block;
        }

        .meta-info {
            margin-bottom: 25px;
        }

        .meta-item {
            margin-bottom: 6px;
        }

        .label {
            font-weight: bold;
        }

        .salutation {
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .content-body p {
            text-align: justify;
            margin-bottom: 18px;
            text-transform: uppercase;
            font-size: 13.5px;
            letter-spacing: 0.3px;
        }

        .credentials-table {
            width: 100%;
            margin-top: 35px;
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        .credentials-table td {
            padding: 8px 0;
            vertical-align: top;
        }

        .footer-sign {
            margin-top: 60px;
            text-align: right;
            float: right;
            width: 250px;
            page-break-inside: avoid;
        }

        .footer-sign .thank-you {
            font-weight: bold;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .footer-sign .company-end {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        {{-- Dynamic Company Name --}}
        <div class="company-name">{{ $company->name ?? 'Sani Infra Height' }}</div>
        <div class="company-subtitle">{{ $company->name ?? 'Sani Infra Height' }}</div>
    </div>

    <div class="title-container">
        <div class="letter-title">Welcome Letter</div>
    </div>

    <div class="meta-info">
        <div class="meta-item">
            <span class="label">MOBILE NO. :</span> {{ $associate->mobile_number ?? '' }}
        </div>
        <div class="meta-item">
            <span class="label">ADDRESS :</span> {{ $associate->address ?? '' }}
        </div>
    </div>

    <div class="salutation">DEAR BUSINESS Channel Partner,</div>

    <div class="content-body">
        <p>HEARTIEST WELCOME TO {{ strtoupper($company->name ?? 'OUR COMPANY') }}</p>
        <p>WE THANK YOU FOR CHOOSING US AS A PATH TO BUILD YOUR CAREER & MAKING YOUR DREAMS COME TRUE!</p>
        <p>WE ARE DELIGHTED TO ADD YOU AS BUSINESS Channel Partner.</p>
        <p>WE LOOK FORWARD FOR YOUR ACTIVE PARTICIPATION IN ALL OUR BUSINESS GROWTH ACTIVITIES & OTHER OFFICIAL
            MEETINGS.</p>
        <p>ANTICIPATING YOUR POSITIVE RESPONSE IN ALL ENDEAVOURS.</p>
        <p>HAPPY SELLING !!</p>
    </div>

    <table class="credentials-table">
        <tr>
            <td style="width: 55%;"><span class="label">YOUR BUSINESS ID :</span> {{ $associate->associate_id ?? '' }}
            </td>
            <td style="width: 45%;"><span class="label">PASSWORD :</span>
                {{ $associate->plain_password ?? '********' }}</td>
        </tr>
        <tr>
            <td><span class="label">SPONSOR ID & NAME :</span> {{ $associate->sponsor->associate_id ?? '' }} /
                {{ $associate->sponsor->associate_name ?? '' }}</td>
            <td><span class="label">PAN :</span> {{ strtoupper($associate->pancard_number ?? '') }}</td>
        </tr>
    </table>

    <div class="footer-sign">
        <div class="thank-you">THANKING YOU</div>
        <div class="company-end">{{ $company->name ?? 'Sani Infra Height' }}</div>
    </div>

</body>

</html>
