<!DOCTYPE html>
<html>

<head>

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
            background: #f2f2f2;
        }
    </style>

</head>

<body>

    <table>

        <thead>

            <tr>

                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach

            </tr>

        </thead>

        <tbody>

            @foreach ($rows as $row)
                <tr>

                    @foreach ($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach

                </tr>
            @endforeach

        </tbody>

    </table>

</body>

</html>
