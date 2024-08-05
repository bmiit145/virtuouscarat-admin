<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p>Thank you for your order. Your order ID is {{ $order_id }}.</p>
    <p>Your login credentials are as follows:</p>
    <p><strong>Email:</strong> {{ $cust_mail }}</p>
    <p><strong>Password:</strong> Your existing password.</p>
    <p><a href="{{ $customerPanelUrl }}">Click here to explore your orders</a></p>
</body>
</html>
