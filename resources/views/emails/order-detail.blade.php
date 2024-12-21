<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px; /* Increased width */
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            background-color: #ffffff;
        }
        .highlight {
            background-color: #e6f7ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Order Date</th>
                    <th>Order No.</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Vendor Name</th>
                    <th>Product Price</th>
                    <th>Order Value</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                @php
                    $rowspan = count($order->products);
                @endphp
                @foreach($order->products as $index => $product)
                    @if($product->product)
                        @php
                            $productAttributes = $product->product->attributes->pluck('value','name');
                            $ProdColor = $productAttributes->get('Color', '');
                            $prodClarity = $productAttributes->get('Clarity', '');
                            $prodCut = $productAttributes->get('Cut', '');
                            $prodMeasurement = $productAttributes->get('Measurement', '');
                        @endphp
                    @endif
                    <tr class="{{ $index == 0 ? 'highlight' : '' }}" data-order_id="{{ $order->order_id }}">
                        @if($index == 0)
                            <td rowspan="{{ $rowspan }}">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $order->order_id }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $order->billing_first_name }} {{ $order->billing_last_name }} <br> {{ $order->billing_email }}</td>
                        @endif
                        <td>
                            @if($product->product)
                                <span>{{ $product->product->sku ?? '' }} <br>
                                <span>{{ $product->product->name }}</span>
                                <span>( Color : {{$ProdColor . ', Clarity : ' . $prodClarity . ', Cut : ' . $prodCut . ', Measurement : ' . $prodMeasurement}} )</span> </td>
                            @endif
                        </td>
                        <td>
                            @if($product->product)
                                <span>{{ $product->product->vendor->name }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->product)
                                <span>₹{{ $product->price }} </span>
                                <sub> ({{ $product->quantity }})</sub>
                            @endif
                        </td>
                        @if($index == 0)
                            <td rowspan="{{ $rowspan }}">₹{{ number_format($order->total, 2) }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
