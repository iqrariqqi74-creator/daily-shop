<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Confirmation</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #333333;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .email-container {
            width: 600px;
            max-width: 90%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        /* =========================
           HEADER
        ========================== */

        .email-header {
            background: #ff6666;
            color: #ffffff;
            text-align: center;
            padding: 28px 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .email-header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.95;
        }

        /* =========================
           BODY
        ========================== */

        .email-body {
            padding: 35px;
        }

        .email-body h2 {
            margin: 0 0 10px;
            font-size: 23px;
            color: #333333;
        }

        .email-body h3 {
            margin: 0 0 15px;
            font-size: 18px;
            color: #333333;
        }

        .email-body p {
            font-size: 14px;
            line-height: 1.7;
            margin: 8px 0;
        }

        .divider {
            border: 0;
            border-top: 1px solid #eeeeee;
            margin: 28px 0;
        }

        /* =========================
           SUCCESS MESSAGE
        ========================== */

        .success-box {
            background: #fff5f5;
            border-left: 4px solid #ff6666;
            padding: 15px 18px;
            margin: 20px 0;
        }

        .success-box p {
            margin: 0;
            color: #555555;
        }

        /* =========================
           ORDER INFORMATION
        ========================== */

        .order-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-info td {
            padding: 10px 0;
            border-bottom: 1px solid #eeeeee;
            font-size: 14px;
        }

        .order-info td:first-child {
            color: #777777;
            width: 45%;
        }

        .order-info td:last-child {
            text-align: right;
            font-weight: bold;
            color: #333333;
        }

        /* =========================
           PRODUCT CARD
        ========================== */

        .product-card {
            border: 1px solid #eeeeee;
            border-radius: 5px;
            padding: 18px;
            margin-bottom: 15px;
            background: #ffffff;
        }

        .product-image {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 1px solid #eeeeee;
            border-radius: 4px;
            background: #fafafa;
            display: block;
            margin-bottom: 12px;
        }

        .product-name {
            font-size: 17px;
            font-weight: bold;
            color: #333333;
            margin: 0 0 12px;
        }

        .product-detail {
            font-size: 13px;
            color: #666666;
            margin: 5px 0;
        }

        .product-total {
            color: #ff6666;
            font-size: 15px;
            font-weight: bold;
        }

        /* =========================
           TOTAL SECTION
        ========================== */

        .total-box {
            background: #fff5f5;
            border: 1px solid #ffd1d1;
            border-radius: 5px;
            padding: 20px;
        }

        .total-row {
            width: 100%;
            margin-bottom: 10px;
        }

        .total-label {
            color: #666666;
            font-size: 14px;
        }

        .total-value {
            float: right;
            font-weight: bold;
            color: #333333;
        }

        .final-total {
            border-top: 1px solid #ffd1d1;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #ff6666;
        }

        /* =========================
           ADDRESS
        ========================== */

        .address-box {
            background: #fafafa;
            border: 1px solid #eeeeee;
            border-radius: 5px;
            padding: 18px;
        }

        .address-box p {
            margin: 4px 0;
        }

        /* =========================
           FOOTER
        ========================== */

        .email-footer {
            padding: 22px;
            background: #fafafa;
            text-align: center;
            color: #777777;
            font-size: 12px;
            line-height: 1.6;
        }

        .brand {
            color: #ff6666;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-placed {
            background: #fff3cd;
            color: #856404;
        }

        .status-on-the-way {
            background: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-processing {
            background: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>

<body>

    <div class="email-wrapper">

        <div class="email-container">

            <!-- =========================
             HEADER
        ========================== -->

            <div class="email-header">

                <h1>DailyShop</h1>

                <p>Order Confirmation</p>

            </div>


            <!-- =========================
             BODY
        ========================== -->

            <div class="email-body">

                <h2>
                    Order Placed Successfully
                </h2>

                <p>
                    Hello <strong>{{ $order->name }}</strong>,
                </p>

                <p>
                    Thank you for shopping with DailyShop.
                    Your order has been placed successfully.
                </p>


                <!-- SUCCESS MESSAGE -->

                <div class="success-box">

                    <p>
                        Your order <strong>#{{ $order->id }}</strong>
                        has been successfully received.
                    </p>

                </div>


                <hr class="divider">


                <!-- =========================
                 ORDER INFORMATION
            ========================== -->

                <h3>Order Information</h3>

                <table class="order-info">

                    <tr>
                        <td>Order ID</td>
                        <td>#{{ $order->id }}</td>
                    </tr>

                    <tr>
                        <td>Order Status</td>
                        <td>

                            @switch((int) $order->order_status)
                                @case(1)
                                    <span class="status-badge status-placed">
                                        Placed
                                    </span>
                                @break

                                @case(2)
                                    <span class="status-badge status-on-the-way">
                                        On The Way
                                    </span>
                                @break

                                @case(3)
                                    <span class="status-badge status-delivered">
                                        Delivered
                                    </span>
                                @break

                                @case(4)
                                    <span class="status-badge status-cancelled">
                                        Cancelled
                                    </span>
                                @break

                                @default
                                    <span class="status-badge">
                                        Unknown
                                    </span>
                            @endswitch

                        </td>
                    </tr>

                    <tr>
                        <td>Payment Type</td>
                        <td>
                            {{ $order->payment_type }}
                        </td>
                    </tr>

                    <tr>
                        <td>Payment Status</td>
                        <td>

                            @switch((int) $order->payment_status)
                                @case(1)
                                    <span class="status-badge status-pending">
                                        Pending
                                    </span>
                                @break

                                @case(2)
                                    <span class="status-badge status-completed">
                                        Completed
                                    </span>
                                @break

                                @case(3)
                                    <span class="status-badge status-cancelled">
                                        Cancelled
                                    </span>
                                @break

                                @case(4)
                                    <span class="status-badge status-processing">
                                        Processing
                                    </span>
                                @break

                                @default
                                    <span class="status-badge">
                                        Unknown
                                    </span>
                            @endswitch

                        </td>
                    </tr>

                </table>


                <hr class="divider">


                <!-- =========================
                 PRODUCTS
            ========================== -->

                <h3>Order Items</h3>

                @foreach ($orderItems as $item)
                    <div class="product-card">

                        @if (!empty($item->attribute_image))
                            <img src="{{ url($item->attribute_image) }}" class="product-image"
                                alt="{{ $item->product_name }}">
                        @endif


                        <p class="product-name">
                            {{ $item->product_name }}
                        </p>


                        <p class="product-detail">
                            <strong>Size:</strong>
                            {{ $item->size ?? 'N/A' }}
                        </p>


                        <p class="product-detail">
                            <strong>Color:</strong>
                            {{ $item->color ?? 'N/A' }}
                        </p>


                        <p class="product-detail">
                            <strong>Price:</strong>
                            {{ $item->price }}
                        </p>


                        <p class="product-detail">
                            <strong>Quantity:</strong>
                            {{ $item->quantity }}
                        </p>


                        <p class="product-detail product-total">
                            Total:
                            {{ $item->price * $item->quantity }}
                        </p>

                    </div>
                @endforeach


                <hr class="divider">


                <!-- =========================
     ORDER TOTAL
========================== -->

                <h3>Order Summary</h3>

                <div class="total-box">

                    <!-- SUB TOTAL -->
                    <div class="total-row">

                        <span class="total-label">
                            Sub Total
                        </span>

                        <span class="total-value">
                            {{ number_format(
                                $orderItems->sum(function ($item) {
                                    return $item->price * $item->quantity;
                                }),
                                2,
                            ) }}
                        </span>

                        <div style="clear:both;"></div>

                    </div>


                    <!-- TAX -->
                    <div class="total-row">

                        <span class="total-label">
                            Tax Amount
                        </span>

                        <span class="total-value">
                            {{ number_format($order->tax_amount ?? 0, 2) }}
                        </span>

                        <div style="clear:both;"></div>

                    </div>


                    <!-- COUPON CODE -->
                    <div class="total-row">

                        <span class="total-label">
                            Coupon Code
                        </span>

                        <span class="total-value">
                            {{ $order->coupon_code ?? 'No Coupon' }}
                        </span>

                        <div style="clear:both;"></div>

                    </div>


                    <!-- DISCOUNT -->
                    <div class="total-row">

                        <span class="total-label">
                            Discount
                        </span>

                        <span class="total-value" style="color:#28a745;">
                            - {{ number_format($order->coupon_value ?? 0, 2) }}
                        </span>

                        <div style="clear:both;"></div>

                    </div>


                    <!-- FINAL TOTAL -->
                    <div class="final-total">

                        Final Total:

                        <span style="float:right;">
                            {{ number_format($order->total_amount ?? 0, 2) }}
                        </span>

                        <div style="clear:both;"></div>

                    </div>

                </div>


                <hr class="divider">


                <!-- =========================
                 DELIVERY ADDRESS
            ========================== -->

                <h3>Delivery Address</h3>

                <div class="address-box">

                    <p>
                        <strong>{{ $order->name }}</strong>
                    </p>

                    <p>
                        {{ $order->mobile }}
                    </p>

                    <p>
                        {{ $order->address }}
                    </p>

                    <p>
                        {{ $order->city }},
                        {{ $order->state }}
                    </p>

                    <p>
                        {{ $order->pin_code }}
                    </p>

                </div>


                <hr class="divider">


                <p>
                    Thank you for shopping with
                    <span class="brand">DailyShop</span>.
                </p>

                <p>
                    We appreciate your business and hope to see you again.
                </p>

            </div>


            <!-- =========================
             FOOTER
        ========================== -->

            <div class="email-footer">

                © {{ date('Y') }} DailyShop.
                All rights reserved.

            </div>

        </div>

    </div>

</body>

</html>
