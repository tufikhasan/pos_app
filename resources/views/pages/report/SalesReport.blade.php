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
                <th>Sub Total</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sales Report</td>
                <td>
                    {{ Carbon\Carbon::parse($fromDate)->format('d-M-y') }}
                    <i>To</i>
                    {{ Carbon\Carbon::parse($toDate)->format('d-M-y') }}
                </td>
                <td>{{ $totalSum->total_sales }}</td>
                <td>{{ $totalSum->total_tax }}</td>
                <td>{{ $totalSum->total }}</td>
                <td>{{ $totalSum->total_qty }} </td>
            </tr>
        </tbody>
    </table>


    <h3>Details</h3>
    <table class="customers">
        <thead>
            <tr>
                <th>SL No:</th>
                <th>Invoice No:</th>
                <th>Customer</th>
                <th>Customer Email</th>
                <th>Sub Total</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Qty</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td>{{ $key + 1 < 10 ? '0' . ($key + 1) : $key + 1 }}</td>
                    <td>#INV_{{ $item->id }}</td>
                    <td>{{ $item['customer']['name'] }}</td>
                    <td>{{ $item['customer']['email'] }}</td>
                    <td>{{ $item->sub_total }}</td>
                    <td>{{ $item->tax }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ $item->total_qty }}</td>
                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('D d-M-y') }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <h3>Sales Products</h3>
    <table class="customers">
        <thead>
            <tr>
                <th>SL No:</th>
                <th>Invoice No:</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale_products as $key => $item)
                <tr>
                    <td>{{ $key + 1 < 10 ? '0' . ($key + 1) : $key + 1 }}</td>
                    <td>#INV_{{ $item->sale_invoice_id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->price * $item->qty }}</td>
                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('D d-M-y') }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
