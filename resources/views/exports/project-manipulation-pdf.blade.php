<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #198754;
            padding-bottom: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #198754;
            margin-bottom: 5px;
        }

        .sub-title {
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #198754;
            color: white;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        tbody td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .status-available {
            color: green;
            font-weight: bold;
        }

        .status-booked {
            color: red;
            font-weight: bold;
        }

        .status-hold {
            color: orange;
            font-weight: bold;
        }

        .status-registry {
            color: blue;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="header">

        <div class="title">
            Project Manipulation Report
        </div>

        <div class="sub-title">
            Generated On : {{ now()->format('d M Y h:i A') }}
        </div>

    </div>


    <table>

        <thead>

            <tr>

                <th>#</th>
                <th>Project</th>
                <th>Block</th>
                <th>Plot Number</th>
                <th>Status</th>
                <th>Updated Date</th>

            </tr>

        </thead>


        <tbody>

            @foreach ($plots as $key => $plot)
                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td>{{ $plot->project?->name }}</td>

                    <td>{{ $plot->block?->block }}</td>

                    <td>{{ $plot->plot_number }}</td>

                    <td>

                        <span class="status-{{ $plot->status }}">

                            {{ ucfirst($plot->status) }}

                        </span>

                    </td>

                    <td>

                        {{ $plot->updated_at->format('d-m-Y h:i A') }}

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</body>

</html>
