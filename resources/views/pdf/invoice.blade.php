<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel+Vue Take-Home Test') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <style>
        .invoice-container {
            margin: 30px;
            border: 1px solid #ccc;
            padding: 30px;
            border-radius: 10px;
        }

        .invoice-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        .invoice-table th,
        .invoice-table td {
            text-align: center;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="wrapper">
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice #{{ $order->id }}</h1>
            <p>{{ $order->created_at }}</p>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h4>Order Details</h4>
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at }}</p>
                <p><strong>Order Status:</strong> {{ $order->status }}</p>
            </div>

            <div class="col-md-6">
                <h4>Customer Information</h4>
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
            </div>
        </div>

        <div class="mt-4">
            <h2>Order Items</h2>
            <table class="table invoice-table">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price per Item</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>${{ number_format($item->price_per_item, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price_per_item * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="3">Total</td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h3 class="text-center lead">
        Thank you for shopping with us!
    </h3>
</div>

</body>

</html>
