<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>{{ $shop . ' - ' . $customer . ' - ' . $invoice['id'] }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon.css') }}" type="text/css">
    <style>
        @media print {

            #print_btns,
            #print_btns * {
                display: none;
            }

            /* #content,
            #content * {
                display: block;
            } */
        }
    </style>
</head>

<body class="bg-default">
    <!-- Main content -->
    <div class="main-content">
        <div class="container mt-5">
            <div class="d-flex justify-content-center row">
                <div class="col-md-8 bg-white rounded shadow">
                    <div class="p-3" id="content">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-uppercase">Invoice</h3>
                                <div class="text-sm"><span class="text-uppercase">Billed:</span><span
                                        class="ml-1">{{ $customer }}</span></div>
                                <div class="text-sm"><span class="text-uppercase">Date:</span><span
                                        class="ml-1">{{ Illuminate\Support\Carbon::parse($invoice['date'])->format('F jS Y') }}</span>
                                </div>
                                <div class="text-sm"><span class="text-uppercase">Order
                                        ID:</span><span class="ml-1">#INV00{{ $invoice['id'] }}</span></div>
                            </div>
                            <div class="col-md-6 text-right mt-3">
                                <h4 class="text-danger mb-0">{{ $shop }}</h4>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product['name'] }}</td>
                                                <td>{{ $product['qty'] }}</td>
                                                <td>{{ $product['price'] }}</td>
                                                <td>${{ $product['price'] * $product['qty'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right">
                            <p><b>Subtotal :</b> ${{ $invoice['sub_total'] }}</p>
                            <p><b>Tax :</b> ${{ $invoice['tax'] }}</p>
                            <p><b>Grand Total (Incl.Tax) :</b> ${{ $invoice['total'] }}</p>
                        </div>
                    </div>
                    <div class="mb-3 d-flex justify-content-between" id="print_btns">
                        <a class="btn btn-danger btn-sm" href="{{ route('sale.page') }}"><i
                                class="far fa-arrow-alt-circle-left"></i> Back </a>
                        <button class="btn btn-primary btn-sm" type="button" id="print_invoice"><i
                                class="fas fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('print_invoice').addEventListener('click', () => {
            print();
        })
    </script>
</body>

</html>
