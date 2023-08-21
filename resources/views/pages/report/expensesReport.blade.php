<html>

<head>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 12px !important;
        }

        .customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .customers tr:hover {
            background-color: #ddd;
        }

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 6px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>

    <h3>Summary</h3>

    <table class="customers">
        <thead>
            <tr>
                <th>Report</th>
                <th>Date</th>
                <th>Total</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Expenses Report</td>
                <td>
                    {{ Carbon\Carbon::parse($fromDate)->format('d-M-y') }}
                    <i>To</i>
                    {{ Carbon\Carbon::parse($toDate)->format('d-M-y') }}
                </td>
                <td>{{ $sum->total_amount }}</td>
                <td>{{ $sum->qty }}</td>
            </tr>
        </tbody>
    </table>


    <h3>Details</h3>
    <table class="customers">
        <thead>
            <tr>
                <th>SL No:</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td>{{ $key + 1 < 10 ? '0' . ($key + 1) : $key + 1 }}</td>
                    <td>{{ $item['expense_category']['name'] }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('D d-M-y') }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
