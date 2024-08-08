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
    <p><strong>Password:</strong>  {{ $psw ?? "Your existing password."  }}</p>
    @php(        $customerPanelUrl = env('CUSTOMER_PANEL_URL' , 'https://customer.virtuouscarat.com/'))
    <p><a href="{{ $customerPanelUrl }}">Click here to explore your orders</a></p>

{{--     reset password link --}}
    @if(isset($reset_password_url))
        <p><a href="{{ $reset_password_url }}">Click here to reset your password</a></p>
    @endif

    <p>Thank you for shopping with us!</p>
</body>
</html>
