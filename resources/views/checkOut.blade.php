<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    {{-- <form id="orderForm" action="/ecpay" method="post"> --}}
    {{-- <form id="orderForm" action="/api/map" method="post"> --}}
    <form id="orderForm" action="/api/logistics" method="post">
        @csrf <!-- 加入 CSRF 欄位 -->

        {{-- <label for="memberId">Member ID:</label>
        <input type="text" id="memberId" name="member_id" required>
        <br>

        <label for="orderAmount">Order Amount:</label>
        <input type="number" id="orderAmount" name="order_amount" required>
        <br>

        <label for="notes">Notes:</label>
        <textarea id="notes" name="notes"></textarea>
        <br>

        <label for="productIds">Product IDs (comma-separated):</label>
        <input type="text" id="productIds" name="productIds[]" required>
        <br> --}}

        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
