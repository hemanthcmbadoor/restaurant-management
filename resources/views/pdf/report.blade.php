<!DOCTYPE html>
<html>
<head>
	<title>Report</title>
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>
<body>
	<h3 style="text-align:center;">Restaurant Management</h3>
    <table>
        <tr>
            <th>Name: {{ $name }}</th>
            <th>Email: {{ $email }}</th>
        </tr>

        <tr>
            <th>Contact Number: {{ $contactNumber }}</th>
            <th>Payment Method: {{ $paymentMethod }}</th>
        </tr>

    </table>

    <h3>Product Details</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>

        @if(count($productDetails) > 0)

            @foreach($productDetails as $product)

                <tr>
                    <td>{{$product['name']}}</td>
                    <td>{{$product['category']}}</td>
                    <td>{{$product['quantity']}}</td>
                    <td>{{$product['price']}}</td>
                    <td>{{$product['total']}}</td>
                </tr>

            @endforeach

        @endif
    </table>
    <h3>Total: {{$totalAmount}}</h3>
</body>
</html>